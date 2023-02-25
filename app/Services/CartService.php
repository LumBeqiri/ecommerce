<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Region;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;

class CartService
{
    public static function calculatePrice(mixed $items): int
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

    public static function saveItemsToCart(mixed $items, User $user, string $region_id): Cart|JsonResponse
    {
        $region = Region::where('uuid', $region_id)->firstOrFail();
        $cart = Cart::updateOrCreate(['user_id' => $user->id], ['region_id' => $region->id]);

        foreach ($items as $item) {
            $variant = Variant::where('uuid', $item['variant_id'])->firstOrFail();

            $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

            if (! $variant->variant_prices()->where('region_id', $region->id)->exists()) {
                return response()->json(['error' => 'Product not available in your region', 'code' => 422], 422, ['application/json']);
            }

            if ($variant->status === 'unavailable') {
                return response()->json(['error' => 'Product is not available', 'code' => 404], 404);
            }

            if ((optional($cart_item)->quantity + $item['count']) > $variant->stock) {
                return response()->json(['error' => 'There are not enough products in stock', 'code' => 422], 422);
            }

            if (isset($cart_item)) {
                $cart_item->quantity += $item['count'];
                $cart_item->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $variant->id,
                    'quantity' => $item['count'],
                ]);
            }
        }

        return $cart;
    }

    public static function saveCookieItemsToCart(mixed $items, User $user, string $region_id): void
    {
        //TODO here get country from IP, find region and save it to that cart
        $region = Region::where('uuid', $region_id)->firstOrFail();
        $cart = Cart::updateOrCreate(['user_id' => $user->id], ['region_id' => $region->id]);

        foreach ($items as $item) {
            $variant = Variant::where('uuid', $item['variant_id'])->firstOrFail();

            $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

            if (! $variant->variant_prices()->where('region_id', $region->id)->exists()) {
                continue;
            }

            if ($variant->status === Product::UNAVAILABLE_PRODUCT) {
                continue;
            }

            if ((optional($cart_item)->quantity + $item['count']) > $variant->stock) {
                continue;
            }

            if (isset($cart_item)) {
                $cart_item->quantity += $item['count'];
                $cart_item->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $variant->id,
                    'quantity' => $item['count'],
                ]);
            }
        }
    }

    private function validateCartItem(CartItem $cart_item, Variant $variant, $quantity)
    {
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
