<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use App\Models\Region;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    // public static function calculatePrice(Collection $variants): int
    // {
    //     // $variant_ids = [];
    //     // $itemCount = count($items);
    //     // for ($i = 0; $i < $itemCount; $i++) {
    //     //     $variant_ids[$i] = $items[$i]['variant_id'];
    //     // }

    //     // $variant_prices = Variant::whereIn('id', $variant_ids)->pluck('price');

    //     // $total = 0;

    //     // foreach ($variant_prices as $price) {
    //     //     $total += (int) $price;
    //     // }

    //     return PriceService::priceToEuro(40);
    // }

    public static function saveItemsToCart(mixed $items): Cart|JsonResponse
    {
        $region_id = Country::select('region_id')->where('id', auth()->user()->country_id)
        ->firstOrFail()
        ->region_id;

        $region = Region::findOrFail($region_id);

        $cart = Cart::updateOrCreate(['user_id' => auth()->id()], ['region_id' => $region->id]);
        // all added variants are in this variable
    
        $variants[]= null;

        foreach ($items as $item) {
            $variant = Variant::where('uuid', $item['variant_id'])->firstOrFail();

            $cart_item = $cart->cart_items()->where('variant_id', $variant->id)->first();

            if (! $variant->variant_prices()->where('region_id', $region->id)->exists()) {
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
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                ]);
            }

            $variants[] = $variant;
            
        }

        // here we pass the variants to a function 
        // to calculate the cart price

        return $cart;
    }

    public static function saveCookieItemsToCart(mixed $items, User $user, string $region_id): void
    {
        $region = Region::where('uuid', $region_id)->firstOrFail();
        $cart = Cart::updateOrCreate(['user_id' => $user->id], ['region_id' => $region->id]);

        $variants[] = null;
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
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                ]);
            }

            $variants[] = $variant;
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
