<?php

namespace App\Services;

use App\Data\DiscountResultData;
use App\Data\VariantDiscountData;
use App\Exceptions\DiscountException;
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
    /**
     * Apply a discount code to a given cart.
     *
     *
     * @throws DiscountException
     */
    public static function applyDiscount(Cart $cart, string $discount_code): DiscountResultData
    {
        if ($cart->has_been_discounted) {
            throw new DiscountException('Cart already discounted!', 422);
        }

        $discount = Discount::with('discount_rule')
            ->where('code', $discount_code)
            ->firstOrFail();

        $discount_rule = $discount->discount_rule;
        $discount_region = $discount_rule->region;

        // Validate discount
        if ($discount->is_disabled || $discount_region === null) {
            throw new DiscountException('Discount is not applicable', 422);
        }

        if (! Carbon::today()->between($discount->starts_at, $discount->ends_at, true)) {
            throw new DiscountException('Discount has expired!', 422);
        }

        // Check the discount type & allocation
        if (
            $discount_rule->discount_type === DiscountRuleTypes::FIXED_AMOUNT
            && $discount_rule->allocation === DiscountAllocationTypes::TOTAL_AMOUNT
        ) {
            // Whole cart discount by a fixed amount
            $updatedCart = self::calculate_whole_cart_discount($discount_rule, $cart, $discount_region);

            return new DiscountResultData(
                cart: $updatedCart,
                variant_discounts: []
            );
        }

        if (
            $discount_rule->discount_type === DiscountRuleTypes::PERCENTAGE
            && $discount_rule->allocation === DiscountAllocationTypes::TOTAL_AMOUNT
        ) {
            // Whole cart discount by a percentage
            $updatedCart = self::calculate_percentage_cart_discount($discount_rule, $cart);

            return new DiscountResultData(
                cart: $updatedCart,
                variant_discounts: []
            );
        }

        $variantDiscounts = self::apply_discount_to_cart_variants($cart, $discount_rule, $discount_region);

        return new DiscountResultData(
            cart: $cart->fresh(),
            variant_discounts: $variantDiscounts
        );
    }

    /**
     * Deduct a fixed amount from the cart's total price.
     *
     *
     * @throws DiscountException
     */
    private static function calculate_whole_cart_discount(
        DiscountRule $discount_rule,
        Cart $cart,
        Region $discount_region
    ): Cart {
        if ($cart->region->id !== $discount_region->id) {
            throw new DiscountException('Discount is not applicable', 422);
        }

        if ($discount_rule->discount_type === DiscountRuleTypes::PERCENTAGE) {
            $discountAmount = ($cart->total_cart_price * $discount_rule->value) / 100;
        } else {
            if ($discount_rule->currency_id !== $cart->region->currency_id) {
                throw new DiscountException('Discount currency does not match cart currency', 422);
            }

            $discountAmount = $discount_rule->currency->has_cents
                ? $discount_rule->value
                : $discount_rule->value * 100;
        }

        $cart->total_cart_price = max(0, $cart->total_cart_price - (int) $discountAmount);
        $cart->has_been_discounted = true;
        $cart->save();

        return $cart;
    }

    /**
     * Deduct a percentage from the cart's total price.
     */
    private static function calculate_percentage_cart_discount(DiscountRule $discount_rule, Cart $cart): Cart
    {
        $discountedPrice = $cart->total_cart_price
            - ($cart->total_cart_price * ($discount_rule->value / 100));

        if ($discountedPrice < 0) {
            $discountedPrice = 0;
        }

        $cart->total_cart_price = (int) round($discountedPrice);
        $cart->has_been_discounted = true;
        $cart->save();

        return $cart;
    }

    /**
     * Apply a discount to each variant within the cart,
     * returning an array of discount data for each variant.
     *
     * @return VariantDiscountData[]
     */
    private static function apply_discount_to_cart_variants(
        Cart $cart,
        DiscountRule $discount_rule,
        Region $discount_region
    ): array {
        $variants = Variant::whereHas('product', function ($query) {
            $query->whereHas('discount', function ($discountQuery) {
                $discountQuery->whereHas('discount_rule', function ($ruleQuery) {
                    $ruleQuery->where('operator', 'in');
                });
            });
        })
            ->whereIn('id', $cart->cart_items->pluck('variant_id'))
            ->get();

        $variant_discount_data = [];

        foreach ($variants as $variant) {
            $product = $variant->product;

            if (! $product->discount) {
                continue;
            }

            $discount_value = $discount_rule->value;
            $discount_type = $discount_rule->discount_type;

            $variant_price = $variant->variant_prices()
                ->where('region_id', $discount_region->id)
                ->value('price');

            $cart_item = $cart->cart_items
                ->where('variant_id', $variant->id)
                ->first();

            $cart_item->discounted_price = self::calculateDiscountedPrice($discount_rule, $variant_price);
            $cart_item->save();

            $variant_discount_data[] = new VariantDiscountData(
                variant: $variant,
                value: $discount_value,
                type: $discount_type
            );
        }

        $cart_total = 0;
        foreach ($cart->cart_items as $item) {
            $cart_total += ($item->discounted_price * $item->quantity);
        }

        $cart->total_cart_price = $cart_total;
        $cart->save();

        return $variant_discount_data;
    }

    /**
     * Calculate discounted price for an individual variant.
     */
    private static function calculateDiscountedPrice(DiscountRule $discount_rule, float|int $variant_price): float
    {
        if ($discount_rule->discount_type === DiscountRuleTypes::FIXED_AMOUNT) {

            return max(0, $variant_price - $discount_rule->value);
        }

        // Percentage discount
        $discounted = $variant_price - (($variant_price * $discount_rule->value) / 100);

        return $discounted < 0 ? 0 : $discounted;
    }
}
