<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use App\Models\CartItem;
use App\Data\CartItemData;
use App\Exceptions\CartException;
use Illuminate\Support\Facades\DB;

class CartService
{
    public static function calculateCartPrice(Cart $cart)
    {
        $price = 0;
        foreach ($cart->cart_items as $cartItem) {
            $variant = Variant::find($cartItem->variant_id);

            $variantPrice = $variant->variant_prices()->where('region_id', $cart->region_id)->firstOrFail();

            $price += $variantPrice->price * $cartItem->quantity;
        }

        $cart->total_cart_price = $price;
        $cart->save();

        return $cart;
    }

    /**
     * Sync the provided cart items with the user's cart.
     * Overwrites current cart items with the ones provided.
     *
     * @param  \App\Data\CartItemData[]  $items  Array of CartItemData instances.
     * @return \App\Models\Cart The updated cart.
     * @throws CartException
     */
    public static function saveItemsToCart(array $items): Cart
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $region = $user->region;

        /** @var \App\Models\Cart $cart */
        $cart = Cart::updateOrCreate(
            ['buyer_id' => $user->buyer->id, 'is_closed' => false],
            ['region_id' => $region->id]
        );

        $cart->load('cart_items');

        $variantIds = collect($items)->pluck('variant_id')->all();

        $variants = Variant::with(['variant_prices' => function ($query) use ($region) {
            $query->where('region_id', $region->id);
        }])->whereIn('ulid', $variantIds)->get();

        DB::transaction(function () use ($items, $variants, $cart, $region) {
            foreach ($items as $item) {
                /** @var \App\Data\CartItemData $item */
                $variant = $variants->firstWhere('ulid', $item->variant_id);
                if (!$variant) {
                    throw new CartException("Variant with ULID {$item->variant_id} not found.");
                }

                // Validate the cart item.
                self::validateCartItem($item, $variant, $cart, $region);

                $variantPrice = $variant->variant_prices()->where('region_id', $region->id)->firstOrFail();

                // Update the variant's stock using optimistic locking.
                self::updateVariantStockOptimistically($variant->ulid, $item->quantity);

                $cartItem = $cart->cart_items->firstWhere('variant_id', $variant->id);
                if ($cartItem) {
                    $cartItem->quantity += $item->quantity;
                    $cartItem->save();
                } else {
                    CartItem::create([
                        'cart_id'          => $cart->id,
                        'variant_id'       => $variant->id,
                        'variant_price_id' => $variantPrice->id,
                        'price'            => $variantPrice->price,
                        'quantity'         => $item->quantity,
                        'currency_id'      => $variantPrice->currency_id,
                    ]);
                }

                // Increase the cart's total price.
                $cart->total_cart_price += $variantPrice->price * $item->quantity;
            }
            $cart->save();
        });

        $cart->refresh();

        return $cart;
    }

    /**
     * Sync the provided cart items with the user's cart.
     * Overwrites current cart items with the ones provided.
     *
     * @param  \App\Data\CartItemData[]  $items  Array of CartItemData instances.
     * @param  \App\Models\User  $user  The user to sync the cart items for.
     * @return \App\Models\Cart The updated cart.
     * @throws CartException
     */
    public static function syncItemsToCart(User $user, array $items): Cart
    {

        $region = $user->region;
    
        /** @var Cart $cart */
        $cart = Cart::updateOrCreate(
            ['buyer_id' => $user->buyer->id, 'is_closed' => false],
            ['region_id' => $region->id]
        );

    
        $cart->load('cart_items');
    
        // Key by variant_id (using the DTO's property)
        $incomingItems = collect($items)->keyBy(fn($item) => $item->variant_id);
    
        // Retrieve variants using ULIDs.
        $variants = Variant::with(['variant_prices' => function ($query) use ($region) {
            $query->where('region_id', $region->id);
        }])->whereIn('ulid', $incomingItems->keys())->get();
    
        DB::transaction(function () use ($incomingItems, $variants, $cart, $region) {
            foreach ($incomingItems as $variantUlid => $itemData) {
                // $itemData is an instance of CartItemData.
                $variant = $variants->firstWhere('ulid', $variantUlid);
                if (!$variant) {
                    continue;
                }
    
                self::validateCartItem($itemData, $variant, $cart, $region);
    
                $variantPrice = $variant->variant_prices()->where('region_id', $region->id)->firstOrFail();
    
                $existingCartItem = $cart->cart_items->firstWhere('variant_id', $variant->id);
                if ($existingCartItem && $itemData->quantity > $existingCartItem->quantity) {
                    // Calculate the additional quantity needed.
                    $diff = $itemData->quantity - $existingCartItem->quantity;
                    self::updateVariantStockOptimistically($variant->ulid, $diff);
                    $existingCartItem->quantity = $itemData->quantity;
                    $existingCartItem->save();
                } elseif ($existingCartItem) {
                    // If decreasing, "release" stock.
                    $diff = $existingCartItem->quantity - $itemData->quantity;
                    self::revertVariantStockOptimistically($variant->ulid, $diff);
                    $existingCartItem->quantity = $itemData->quantity;
                    $existingCartItem->save();
                } else {
                    self::updateVariantStockOptimistically($variant->ulid, $itemData->quantity);
                    CartItem::create([
                        'cart_id'          => $cart->id,
                        'variant_id'       => $variant->id,
                        'variant_price_id' => $variantPrice->id,
                        'price'            => $variantPrice->price,
                        'quantity'         => $itemData->quantity,
                        'currency_id'      => $variantPrice->currency_id,
                    ]);
                }
            }
    
            $providedVariantIds = $variants->pluck('id')->toArray();
            $cart->cart_items()->whereNotIn('variant_id', $providedVariantIds)->delete();
    
            self::calculateCartPrice($cart);
            $cart->save();
        });
    
        $cart->refresh();
    
        return $cart;
    }
    
    

    /**
     * Remove a specified quantity of an item from the user's cart.
     * This method will also revert the reserved stock using optimistic locking.
     *
     * @param  array  $data  The validated data, including 'variant_id' and 'quantity'.
     * @return \App\Models\Cart The updated cart.
     * @throws CartException
     */
    public static function removeItemFromCart(array $data): Cart
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $cart = Cart::where('buyer_id', $user->buyer->id)
                    ->where('is_closed', false)
                    ->firstOrFail();

        $variant = Variant::where('ulid', $data['variant_id'])->first();
        if (!$variant) {
            throw new CartException("Variant not found");
        }

        $cartItem = $cart->cart_items()->where('variant_id', $variant->id)->first();
        if (!$cartItem) {
            throw new CartException("Cart item not found");
        }

        if ($cartItem->quantity < $data['quantity']) {
            throw new CartException("You have less than {$data['quantity']} items in your cart");
        }

        self::revertVariantStockOptimistically($variant->ulid, $data['quantity']);

        $cartItem->quantity -= $data['quantity'];
        if ($cartItem->quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->save();
        }

        self::calculateCartPrice($cart);
        $cart->save();
        $cart->refresh();

        return $cart;
    }


    private static function validateCartItem(CartItemData $item, $variant, $cart, $region): bool
    {
        if ($variant->status === Product::UNAVAILABLE_PRODUCT) {
            throw new CartException('Product is not available', 404);
        }

        if ($variant->publish_status === Product::DRAFT) {
            throw new CartException('Product is not available', 404);
        }

        if ($item->quantity > $variant->stock) {
            throw new CartException('There are not enough products in stock', 422);
        }

        $cart_item = $cart->cart_items->where('variant_id', $variant->id)->first();

        if (isset($cart_item) && ($cart_item->quantity + $item->quantity > $variant->stock)) {
            throw new CartException('There are not enough products in stock', 422);
        }

        return true;
    }



     /**
     * Attempt to update (reduce) the variant stock using optimistic locking.
     *
     * @param  int|string  $variantIdentifier  The variant's ULID.
     * @param  int  $quantity  The quantity to reduce.
     * @throws CartException
     */
    protected static function updateVariantStockOptimistically($variantIdentifier, int $quantity): void
    {
        $maxRetries = 3;
        $attempt = 0;

        while ($attempt < $maxRetries) {
            // Retrieve the variant using the ULID.
            $variant = Variant::where('ulid', $variantIdentifier)->first();
            if (!$variant) {
                throw new CartException("Variant not found");
            }

            // Skip locking if inventory is not managed.
            if (!$variant->manage_inventory) {
                return;
            }

            // When reducing stock, ensure there is enough available.
            if ($variant->stock < $quantity) {
                throw new CartException("Insufficient stock for variant {$variant->id}");
            }

            // Calculate new stock.
            $newStock = $variant->stock - $quantity;
            $currentVersion = $variant->lock_version;

            $affectedRows = Variant::where('id', $variant->id)
                ->where('lock_version', $currentVersion)
                ->update([
                    'stock' => $newStock,
                    'lock_version' => DB::raw('lock_version + 1'),
                ]);

            if ($affectedRows) {
                return; // Update succeeded.
            }

            $attempt++;
        }

        throw new CartException("Failed to update variant stock due to concurrent modifications. Please try again.");
    }

     /**
     * Attempt to revert (increase) the variant stock using optimistic locking.
     *
     * @param  int|string  $variantIdentifier  The variant's ULID.
     * @param  int  $quantity  The quantity to add back.
     * @throws CartException
     */
    protected static function revertVariantStockOptimistically($variantIdentifier, int $quantity): void
    {
        $maxRetries = 3;
        $attempt = 0;

        while ($attempt < $maxRetries) {
            $variant = Variant::where('ulid', $variantIdentifier)->first();
            if (!$variant) {
                throw new CartException("Variant not found");
            }

            if (!$variant->manage_inventory) {
                return;
            }

            $newStock = $variant->stock + $quantity;
            $currentVersion = $variant->lock_version;

            $affectedRows = Variant::where('id', $variant->id)
                ->where('lock_version', $currentVersion)
                ->update([
                    'stock' => $newStock,
                    'lock_version' => DB::raw('lock_version + 1'),
                ]);

            if ($affectedRows) {
                return; // Reversion succeeded.
            }

            $attempt++;
        }

        throw new CartException("Failed to revert variant stock due to concurrent modifications. Please try again.");
    }
}
