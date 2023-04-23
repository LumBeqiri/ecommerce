<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\Variant;

class DiscountService
{
    public static function applyDiscount(Cart $cart, Variant $variant, $discount_code)
    {

        
        // check if it is "in" or "not in" the list of products : DiscountCondition Model
        
        // if it is "in" : check type value of discount and allocation (item_specific, total ammount)
        // maybe receive a list of variants from the client and then check discount conditions on those
        // after that return key - value array for each variant and i'ts discount
        
        // $discount = Discount::where('code', $discount_code)->get();
        $discount = Discount::where('code', $discount_code)->firstOrFail();

        $discount_region = $discount->regions()->where('region_id', auth()->user()->country->region->id)->first();
     
        if ($discount_region === null || $discount->is_disabled) {
            return response()->json(['error' => 'Discount is not applicable', 'code' => 422], 422);
        }

        $product = $variant->product;

        $discount_condition_product = $product->discount_conditions()->exists();
        if ($discount_condition_product) {
            $variant = Variant::with('product.discount_conditions.discount_rule.discount')->where('id', $variant->id)->first();

dd( 
    $variant
);
        } else {
            dd('no discount');
        }
        $discount_value = $variant->product->discount_conditions->first()->discount_rule->value;
        $discount_type = $variant->product->discount_conditions->first()->discount_rule->discount_type;
    }
}
