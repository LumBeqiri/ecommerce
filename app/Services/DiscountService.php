<?php

namespace App\Services;

use App\Exceptions\DiscountException;
use App\Http\Resources\VariantResource;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\DiscountRule;
use App\Models\Region;
use App\Models\Variant;
use App\values\DiscountAllocationTypes;
use App\values\DiscountRuleTypes;
use Illuminate\Support\Carbon;

class DiscountService
{
    public static function applyDiscount(Cart $cart, string $discount_code)
    {
        if ($cart->has_been_discounted) {
            throw new DiscountException('Cart already discounted!', 422);
        }

        $discount = Discount::with('discount_rule')->where('code', $discount_code)->firstOrFail();
        $discount_rule = $discount->discount_rule;
        $discount_region = $discount_rule->region;

        if ($discount->is_disabled || $discount_region === null) {
            throw new DiscountException('Discount is not applicable', 422);
        }

        if (! Carbon::today()->between($discount->starts_at, $discount->ends_at, true)) {
            throw new DiscountException('Discount has expired!');
        }

        // it discounts the whole cart total price with a fixed amount
        if ($discount_rule->discount_type === DiscountRuleTypes::FIXED_AMOUNT && $discount_rule->allocation === DiscountAllocationTypes::TOTAL_AMOUNT) {
            return self::calculate_whole_cart_discount($discount_rule, $cart, $discount_region);
        }

        // it discounts the whole cart total price with a fixed percentage
        if ($discount_rule->discount_type === DiscountRuleTypes::PERCENTAGE && $discount_rule->allocation === DiscountAllocationTypes::TOTAL_AMOUNT) {
            return self::calculate_percentage_cart_discount($discount_rule, $cart);
        }

        return self::apply_discount_to_cart_variants($cart, $discount_rule, $discount_region);
    }

    private static function calculate_whole_cart_discount(DiscountRule $discount_rule, Cart $cart, Region $discount_region): Cart
    {

        if ($cart->region->id 
        !== $discount_region->id) {
            throw new DiscountException('Discount is not applicable', 422);
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

        return $cart;
    }

    private static function calculate_percentage_cart_discount(DiscountRule $discount_rule, Cart $cart): Cart
    {
        $cart->total_cart_price = $cart->total_cart_price - ($cart->total_cart_price * ($discount_rule->value / 100));
        if ($cart->total_cart_price < 0) {
            $cart->total_cart_price = 0;
        }
        $cart->has_been_discounted = true;
        $cart->save();

        return $cart;
    }

    private static function apply_discount_to_cart_variants(Cart $cart, DiscountRule $discount_rule, Region $discount_region): array
    {

        $variants = Variant::whereHas('product', function ($query) {
            $query->whereHas('discount', function ($discountQuery) {
                $discountQuery->whereHas('discount_rule', function ($ruleQuery) {
                    $ruleQuery->where('operator', 'in');
                });
            });
        })
            ->whereIn('id', $cart->cart_items->pluck('variant_id'))
            ->get();

        $variant_discount = [];

        foreach ($variants as $variant) {
            $product = $variant->product;

            if (! $product->discount) {
                continue;
            }

            $discount_value = $discount_rule->value;
            $discount_type = $discount_rule->discount_type;

            $variant_price = $variant->variant_prices()->where('region_id', $discount_region->id)->value('price');

            $cart_item = $cart->cart_items->where('variant_id', $variant->id)->first();

            $cart_item->discounted_price = self::calculateDiscountedPrice($discount_rule, $variant_price);
            $cart_item->save();

            $temp = [
                'variant' => new VariantResource($variant),
                'value' => $discount_value,
                'type' => $discount_type,
            ];

            $variant_discount[] = $temp;
        }

        $cart_total = 0;
        foreach ($cart->cart_items as $item) {
            $cart_total += $item->discounted_price * $item->quantity;
        }

        $cart->total_cart_price = $cart_total;
        $cart->save();

        return $variant_discount;
    }

    private static function calculateDiscountedPrice(DiscountRule $discount_rule, $variant_price)
    {
        if ($discount_rule->discount_type === DiscountRuleTypes::FIXED_AMOUNT) {
            return max(0, $variant_price - $discount_rule->value);
        } else {

            return $variant_price - (($variant_price * $discount_rule->value) / 100);
        }
    }
}
