<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use App\Models\CartItem;
use App\Data\CartItemData;
use App\Exceptions\CartException;

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
 * This method overwrites the current cart items with the ones provided,
 * and removes any items that are no longer present.
 * 
 * @param \App\Data\CartItemData[] $items Array of CartItemData instances.
 * @return \App\Models\Cart The updated cart.
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
    
    // Collect variant IDs from the DTO objects.
    $variant_ids = collect($items)->pluck('variant_id')->all();
    
    $variants = Variant::with(['variant_prices' => function ($query) use ($region) {
        $query->where('region_id', $region->id);
    }])->whereIn('ulid', $variant_ids)->get();
    
    foreach ($items as $item) {
        /** @var \App\Data\CartItemData $item */
        // Find the matching variant using the DTO's variant_id.
        $variant = $variants->first(function ($variant) use ($item) {
            return $variant->ulid === $item->variant_id;
        });
        
        // Find an existing cart item for the variant.
        $cart_item = $cart->cart_items->firstWhere('variant_id', $variant->id);
        
        // Validate the cart item.
        self::validateCartItem($item, $variant, $cart, $region);
        
        $variantPrice = $variant->variant_prices()->where('region_id', $region->id)->firstOrFail();
        
        if ($cart_item) {
            // Update quantity by adding the new amount.
            $cart_item->quantity += $item->quantity;
            $cart_item->save();
        } else {
            // Create a new cart item with the locked price.
            $cart_item = CartItem::create([
                'cart_id'           => $cart->id,
                'variant_id'        => $variant->id,
                'variant_price_id'  => $variantPrice->id,
                'price'             => $variantPrice->price,
                'quantity'          => $item->quantity,
                'currency_id'       => $variantPrice->currency_id, // if your variant price has currency_id
            ]);
        }
        
        $cart->total_cart_price += $variantPrice->price * $item->quantity;
    }
    
    $cart->save();
    $cart->refresh();
    
    return $cart;
}

    /**
     * Sync the provided cart items with the user's cart.
     * This method overwrites the current cart items with the ones provided,
     * and removes any items that are no longer present.
     * 
     * @param \App\Data\CartItemData[] $items Array of CartItemData instances.
     * @param \App\Models\User $user The user to sync the cart items for.
     * @return \App\Models\Cart The updated cart.
     */
    public static function syncItemsToCart(User $user, array $items): Cart
    {

        $region = $user->region;

        // Get or create the current open cart.
        /** @var Cart $cart */
        $cart = Cart::updateOrCreate(
            ['buyer_id' => $user->buyer->id, 'is_closed' => false],
            ['region_id' => $region->id]
        );

        $cart->load('cart_items');


        $incomingItems = collect($items)->keyBy('variant_id');

        // Retrieve the variants that match the provided variant ulids.
        $variants = Variant::with(['variant_prices' => function ($query) use ($region) {
            $query->where('region_id', $region->id);
        }])->whereIn('ulid', $incomingItems->keys())->get();

        foreach ($incomingItems as $variantUlid => $itemData) {
            $variant = $variants->firstWhere('ulid', $variantUlid);
            if (! $variant) {
                continue;
            }

            self::validateCartItem($itemData, $variant, $cart, $region);

            $variantPrice = $variant->variant_prices()->where('region_id', $region->id)->firstOrFail();

            $existingCartItem = $cart->cart_items->firstWhere('variant_id', $variant->id);
            if ($existingCartItem) {
                $existingCartItem->quantity = $itemData['quantity'];
                $existingCartItem->save();
            } else {
                CartItem::create([
                    'cart_id'           => $cart->id,
                    'variant_id'        => $variant->id,
                    'variant_price_id'  => $variantPrice->id,
                    'price'             => $variantPrice->price,
                    'quantity'          => $itemData->quantity,
                    'currency_id'       => $variantPrice->currency_id, // Assuming variant_price has currency_id
                ]);
            }
        }

        $providedVariantIds = $variants->pluck('id')->toArray();
        $cart->cart_items()->whereNotIn('variant_id', $providedVariantIds)->delete();

        self::calculateCartPrice($cart);
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
}
