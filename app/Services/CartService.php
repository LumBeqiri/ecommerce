<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use App\Models\CartItem;

class CartService
{
    /**
     * @param  mixed  $items
     * @return int
     */
    public static function calculatePrice($items)
    {
        $variant_ids = [];
        $itemCount = count($items);
        for ($i = 0; $i < $itemCount; $i++) {
            $variant_ids[$i] = $items[$i]['variant_id'];
        }

        $variant_prices = Variant::whereIn('id', $variant_ids)->pluck('price');

        $total = 0;

        foreach ($variant_prices as $price) {
            $total += (int) $price;
        }

        return PriceService::priceToEuro($total);
    }

    public static function saveItemsToCart(mixed $items, User $user) : Cart
    {
        $cart = Cart::updateOrCreate(['user_id' => $user->id]);

        foreach ($items as $item) {
            $variant = Variant::where('uuid', $item['variant_id'])->firstOrFail();

            $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first  ();

            if ($variant->status === 'unavailable') {
                return response()->json(['error' => 'Product is not available', 'code' => 404], 404);
            }

            if ((optional($cart_item)->count + $item['count']) > $variant->stock) {
                return response()->json(['error' => 'There are not enough products in stock', 'code' => 404], 404);
            }

            if (isset($cart_item)) {
                $cart_item->count += $item['count'];
                $cart_item->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $variant->id,
                    'count' => $item['count'],
                ]);
            }
        }

        return $cart;
    }

    private function validateCartItem(CartItem $cart_item, Variant $variant, $quantity){
        
    }

    public static function validateCookieItems(array $items): bool
    {
        foreach ($items as $item) {
            if (Variant::find($item['variant_id'])) {
                return false;
            }
            if ($item['count'] < 1) {
                return false;
            }
        }

        return true;
    }
}
