<?php

namespace App\Services;

use App\Exceptions\CartException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Variant;

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
    }

    public static function saveItemsToCart(mixed $items): Cart
    {
        $region = auth()->user()->region;

        /**
         * @var Cart $cart
         */
        $cart = Cart::with('cart_items')->updateOrCreate(['buyer_id' => auth()->user()->buyer->id], ['region_id' => $region->id]);

        $variant_ids = array_column($items, 'variant_id');

        $variants = Variant::with(['variant_prices' => function ($query) use ($region) {
            $query->where('region_id', $region->id);
        }])->whereIn('uuid', $variant_ids)->get();

        foreach ($items as $item) {
            $variant = $variants->first(function ($variant) use ($item) {
                return $variant->uuid === $item['variant_id'];
            });

            $cart_item = $cart->cart_items->where('variant_id', $variant->id)->first();

            self::validateCartItem($item, $variant, $cart, $region);

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

    private static function validateCartItem(array $item, $variant, $cart, $region): bool
    {
        if ($variant->status === Product::UNAVAILABLE_PRODUCT) {
            throw new CartException('Product is not available', 404);
        }

        if ($variant->publish_status === Product::DRAFT) {
            throw new CartException('Product is not available', 404);
        }

        if ($item['quantity'] > $variant->stock) {
            throw new CartException('There are not enough products in stock', 422);
        }

        $cart_item = $cart->cart_items->where('variant_id', $variant->id)->first();

        if (isset($cart_item) && ($cart_item->quantity + $item['quantity'] > $variant->stock)) {
            throw new CartException('There are not enough products in stock', 422);
        }

        return true;
    }
}
