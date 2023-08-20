<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\DiscountRule;
use App\Models\Region;
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
        $discount_region = $discount_rule->region;

        if ($discount->is_disabled || $discount_region === null) {
            return response()->json(['error' => 'Discount is not applicable', 'code' => 422], 422);
        }

        // it discounts the whole cart total price with a fixed amount
        if ($discount_rule->discount_type === DiscountRuleTypes::FIXED_AMOUNT && $discount_rule->allocation === DiscountAllocationTypes::TOTAL_AMOUNT) {
            return self::calculate_whole_cart_discount($discount_rule, $cart, $discount_region);
        }

        // it discounts the whole cart total price with a fixed percentage
        if ($discount_rule->discount_type === DiscountRuleTypes::PERCENTAGE && $discount_rule->allocation === DiscountAllocationTypes::TOTAL_AMOUNT) {
            self::calculate_percentage_cart_discount($discount_rule, $cart);
        }

        $variants = Variant::with('product.discount_conditions')
        ->whereIn('id', $cart->cart_items->pluck('variant_id'))
        ->get();

        if ($discount_rule->discount_conditions()->exists()) {
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
        }

        return $variant_discount;
    }

    private static function calculate_whole_cart_discount(DiscountRule $discount_rule, Cart $cart, Region $discount_region)
    {
        $value = 0;
        if ($cart->region->id !== $discount_region->id) {
            return response()->json(['error' => 'Discount is not applicable', 'code' => 422], 422);
        }
        if ($discount_region->currency->has_cents) {
            $value = $discount_rule->value * 100;
        }

        $value = $discount_rule->value;

        $cart->total_cart_price -= (int) $value;

        if ($cart->total_cart_price < 0) {
            $cart->total_cart_price = 0;
        }
        $cart->has_been_discounted = true;
        $cart->save();

        return response()->json(['message' => 'Discount applied successfully', 'code' => 200], 200);
    }

    private static function calculate_percentage_cart_discount(DiscountRule $discount_rule, Cart $cart)
    {
        $cart->total_cart_price = $cart->total_cart_price - ($cart->total_cart_price * ($discount_rule->value / 100));
        if ($cart->total_cart_price < 0) {
            $cart->total_cart_price = 0;
        }
        $cart->has_been_discounted = true;
        $cart->save();

        return response()->json(['message' => 'Discount applied successfully', 'code' => 200], 200);
    }
}
