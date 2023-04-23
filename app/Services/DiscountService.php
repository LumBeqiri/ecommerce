<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\Variant;

class DiscountService
{
    public static function applyDiscount(Cart $cart, $discount_code)
    {
        $variant_discount = [];

        $discount = Discount::where('code', $discount_code)->firstOrFail();

        $discount_region = $discount->regions()->where('region_id', auth()->user()->country->region->id)->first();

        if ($discount_region === null || $discount->is_disabled) {
            return response()->json(['error' => 'Discount is not applicable', 'code' => 422], 422);
        }

        $variants = Variant::with('product.discount_conditions.discount_rule.discount')
        ->whereIn('id', $cart->cart_items->pluck('variant_id'))
        ->get();

        foreach ($variants as $variant) {
            $product = $variant->product;

            $discount_condition_product = $product->discount_conditions()->exists();

            if ($discount_condition_product) {
                $discount_rule = $variant->product->discount_conditions->first()->discount_rule;

                $discount_value = $discount_rule->value;
                $discount_type = $variant->product->discount_conditions->first()->discount_rule->discount_type;

                $temp = [
                    'variant' => $variant->uuid,
                    'value' => $discount_value,
                    'type' => $discount_type,
                    'allocation' => $discount_rule->allocation,
                ];

                $variant_discount[] = $temp;
            }
        }
    }


    public function calculateDiscount(Cart $cart){
        
    }
}
