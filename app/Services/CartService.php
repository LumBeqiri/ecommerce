<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;

class CartService
{
    public static function calculateCartPrice(Cart $cart, int $regionId)
    {
        $price = 0;

        // calculate discounts
        foreach ($cart->cart_items as $cartItem) {
            $variant = Variant::find($cartItem->variant_id);

            $variantPrice = $variant->variant_prices()->where('region_id', $regionId)->firstOrFail();

            $price += $variantPrice->price * $cartItem->quantity;
        }

        $cart->total_cart_price = $price;
        $cart->save();
    }

    public static function saveItemsToCart(mixed $items, User $user)//: Cart|JsonResponse
    {
        $region = $user->country->region;

        $cart = Cart::with(['cart_items.variant.variant_prices' => function ($query) use ($region) {
            $query->where('region_id', $region->id);
        },
        ])->updateOrCreate(['user_id' => auth()->id()], ['region_id' => $region->id]);

        foreach ($items as $item) {
            $variant = Variant::where('uuid', $item['variant_id'])->firstOrFail();

            $cart_item = $cart->cart_items->where('variant_id', $variant->id)->first();

            if (! $variant->variant_prices->where('region_id', $region->id)->count() > 0) {
                return response()->json(['error' => 'Product not available in your region', 'code' => 422], 422, ['application/json']);
            }

            if ($variant->status === Product::UNAVAILABLE_PRODUCT) {
                return response()->json(['error' => 'Product is not available', 'code' => 404], 404);
            }

            if ((optional($cart_item)->quantity + $item['quantity']) > $variant->stock) {
                return response()->json(['error' => 'There are not enough products in stock', 'code' => 422], 422);
            }
            if ($variant->publish_status === Product::DRAFT) {
                return response()->json(['error' => 'There are not enough products in stock', 'code' => 422], 422);
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

        return $cart;
    }

    public static function saveCookieItemsToCart(mixed $items, User $user): void
    {
        $region = $user->country->region;
        $cart = Cart::with(['cart_items.variant.variant_prices' => function ($query) use ($region) {
            $query->where('region_id', $region->id);
        },
        ])->updateOrCreate(['user_id' => $user->id], ['region_id' => $region->id]);

        foreach ($items as $item) {
            $variant = Variant::where('uuid', $item['variant_id'])->firstOrFail();

            $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

            if (! $variant->variant_prices()->where('region_id', $region->id)->exists()) {
                continue;
            }

            if ($variant->status === Product::UNAVAILABLE_PRODUCT) {
                continue;
            }

            if ($variant->publish_status === Product::DRAFT) {
                continue;
            }
            if ((optional($cart_item)->quantity + $item['quantity']) > $variant->stock) {
                continue;
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
    }
}
