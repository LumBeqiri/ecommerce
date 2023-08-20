<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CartService
{
    protected static $error_message;

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
    }

    public static function saveItemsToCart(mixed $items, User $user): Cart|JsonResponse
    {
        $region = $user->country->region;

        /**
         * @var Cart $cart
         */
        $cart = Cart::with('cart_items')->updateOrCreate(['user_id' => auth()->id()], ['region_id' => $region->id]);

        $variant_ids = array_column($items, 'variant_id');

        $variants = Variant::with(['variant_prices' => function ($query) use ($region) {
            $query->where('region_id', $region->id);
        }])->whereIn('uuid', $variant_ids)->get();

        foreach ($items as $item) {
            $variant = $variants->first(function ($variant) use ($item) {
                return $variant->uuid === $item['variant_id'];
            });

            $cart_item = $cart->cart_items->where('variant_id', $variant->id)->first();

            if (! self::validateCartItem($item, $variant, $cart, $region)) {
                return CartService::$error_message;
            }

            if (isset($cart_item)) {
                $cart_item->quantity += $item['quantity'];
                $cart_item->save();
            } else {
                $cart_item = CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                ]);
            }
            $variantPrice = $variant->variant_prices->where('region_id', $region->id)->firstOrFail();
            $cart->total_cart_price += $variantPrice->price * $item['quantity'];
        }

        $cart->save();

        $cart->refresh();

        return $cart;
    }

    // public static function saveCookieItemsToCart(mixed $items, User $user): void
    // {
    //     $region = $user->country->region;
    //     $cart = Cart::with(['cart_items.variant.variant_prices' => function ($query) use ($region) {
    //         $query->where('region_id', $region->id);
    //     },
    //     ])->updateOrCreate(['user_id' => $user->id], ['region_id' => $region->id]);

    //     foreach ($items as $item) {
    //         $variant = Variant::where('uuid', $item['variant_id'])->firstOrFail();

    //         if (! self::validateCartItem($item, $variant, $cart, $region)) {
    //             Log::info(CartService::$error_message);
    //             continue;
    //         }

    //         Log::info($variant->id . ' - stock: ' . $item['quantity']);

    //         $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

    //         if (isset($cart_item)) {
    //             $cart_item->quantity += $item['quantity'];
    //             $cart_item->save();
    //         } else {
    //             $cart_item = CartItem::create([
    //                 'cart_id' => $cart->id,
    //                 'variant_id' => $variant->id,
    //                 'quantity' => $item['quantity'],
    //             ]);
    //         }

    //         $variantPrice = $variant->variant_prices->where('region_id', $region->id)->firstOrFail();
    //         $cart->total_cart_price += $variantPrice->price * $item['quantity'];
    //     }

    //     $cart->refresh();

    //     $cart->save();
    // }

    private static function validateCartItem(array $item, $variant, $cart, $region): bool
    {
        if ($variant->status === Product::UNAVAILABLE_PRODUCT) {
            CartService::$error_message = response()->json(['error' => 'Product is not available', 'code' => 404], 404);

            return false;
        }

        if ($variant->publish_status === Product::DRAFT) {
            CartService::$error_message = response()->json(['error' => 'Product is not available', 'code' => 404], 404);

            return false;
        }

        if ($item['quantity'] > $variant->stock) {
            CartService::$error_message = response()->json(['error' => 'There are not enough products in stock', 'code' => 422], 422);

            return false;
        }

        $cart_item = $cart->cart_items->where('variant_id', $variant->id)->first();

        if (isset($cart_item) && ($cart_item->quantity + $item['quantity'] > $variant->stock)) {
            CartService::$error_message = response()->json(['error' => 'There are not enough products in stock', 'code' => 422], 422);

            return false;
        }

        return true;
    }
}
