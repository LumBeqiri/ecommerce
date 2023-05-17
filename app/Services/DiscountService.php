<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\Variant;
use App\values\DiscountAllocationTypes;
use App\values\DiscountConditionOperatorTypes;
use App\values\DiscountRuleTypes;
use Illuminate\Http\JsonResponse;

class DiscountService
{
    public static function applyDiscount(Cart $cart, string $discount_code): array|JsonResponse
    {
        if ($cart->has_been_discounted) {
            return response()->json(['error' => 'Discount is not applicable', 'code' => 422], 422);
        }

        $variant_discount = [];

        $discount = Discount::with('discount_rule')->where('code', $discount_code)->firstOrFail();
        $discount_rule = $discount->discount_rule;

        $user_region = auth()->user()->country->region->with('currency')->first();

        $discount_region = $discount->regions()->where('region_id', $user_region->id)->first();

        if ($discount->is_disabled) {
            return response()->json(['error' => 'Discount is not applicable', 'code' => 422], 422);
        }

        if ($discount_region === null) {
            return response()->json(['error' => 'Discount is not applicable', 'code' => 422], 422);
        }

        // whole cart discount
        // fixed amount
        if ($discount_rule->discount_type === DiscountRuleTypes::FIXED_AMOUNT && $discount_rule->allocation === DiscountAllocationTypes::TOTAL_AMOUNT) {
            $cart->total_cart_price -= $discount_rule->value;
            $cart->has_been_discounted = true;
            $cart->save();

            return response()->json(['message' => 'Discount applied successfully', 'code' => 200], 200);
        }

        // whole cart discount
        // percentage
        if ($discount_rule->discount_type === DiscountRuleTypes::PERCENTAGE && $discount_rule->allocation === DiscountAllocationTypes::TOTAL_AMOUNT) {
            $cart->total_cart_price = $cart->total_cart_price - ($cart->total_cart_price * ($discount_rule->value / 100));
            $cart->has_been_discounted = true;
            $cart->save();

            return response()->json(['message' => 'Discount applied successfully', 'code' => 200], 200);
        }

        $variants = Variant::with('product.discount_conditions')
        ->whereIn('id', $cart->cart_items->pluck('variant_id'))
        ->get();

        foreach ($variants as $variant) {
            $product = $variant->product;

            $discount_conditions = $product->discount_conditions()->where('discount_rule_id', $discount_rule->id)->get(['operator']);

            foreach ($discount_conditions as $discount_condition) {
                if ($discount_condition->operator === DiscountConditionOperatorTypes::NOT_IN) {
                    continue;
                }
                $discount_value = $discount_rule->value;
                $discount_type = $discount_rule->discount_type;

                $temp = [
                    'variant' => $variant->uuid,
                    'value' => $discount_value,
                    'type' => $discount_type,
                ];

                $variant_discount[] = $temp;
            }
        }

        return $variant_discount;
    }
}
