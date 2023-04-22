<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\Variant;

class DiscountService
{
    public static function applyDiscount(Cart $cart, Variant $variant, $discount_code)
    {
        // check discount code

        // check if product has discount

        // check if it is "in" or "not in" the list of products : DiscountCondition Model

        // if it is "in" : check type value of discount and allocation (item_specific, total ammount)

        $discount_code = 'numquam';
        $discount = Discount::where('code', $discount_code)->first();

        if ($discount) {
            $product = $variant->product;
            $discount_condition_product = $product->discount_conditions()->exists();
            if ($discount_condition_product) {
                $variant = Variant::with('product.discount_conditions.discount_rule.discount')->where('id', $variant->id)->first();
                dd('has discount');
            } else {
                dd('no discount');
            }
        }
        $discount_value = $variant->product->discount_conditions->first()->discount_rule->value;
        $discount_type = $variant->product->discount_conditions->first()->discount_rule->discount_type;
    }
}
