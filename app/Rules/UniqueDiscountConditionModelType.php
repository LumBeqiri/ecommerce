<?php

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\InvokableRule;

class UniqueDiscountConditionModelType implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $discount = request()->route('discount');
        $count = DB::table('discount_conditions')
        ->where('model_type', $value)
        ->where('id', '!=', $discount ? $discount->id : null)
        ->count();

        if($count !== 0){
            $fail('The model_type already exists for this discount condition');
        }
    }
}
