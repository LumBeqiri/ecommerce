<?php

namespace App\Http\Requests;

use App\values\DiscountAllocationTypes;
use App\values\DiscountRuleTypes;
use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'discount_type' => 'required|in:' . DiscountRuleTypes::FIXED . ',' . DiscountRuleTypes::FREE_SHIPPING . ',' . DiscountRuleTypes::PERCENTAGE,
            'allocation' => 'required_if:discount_type,' .  '|in:' . DiscountAllocationTypes::ITEM_SPICIFIC . ',' . DiscountAllocationTypes::TOTAL_AMOUNT,
            'regions' => 'array',
            'regions.*' => 'integer|exists:regions,uuid',
            'code' => 'required|string',
            'percentage' => 'required_if:discount_type,' . DiscountRuleTypes::PERCENTAGE . '|numeric',
            'amount' => 'required_if:discount_type,' . DiscountRuleTypes::FIXED . '|numeric',
            'description' => 'required|string|max:255',
            'is_dynamic' => 'nullable|boolean',
            'starts_at' => 'nullable|timestamp',
            'ends_at' => 'nullable|timestamp',
            'usage_limit' => 'nullable|number',
            'conditions' => 'required|boolean',
            'operator' => 'required_if: conditions, 1|in:in,not_in',
            'products' => 'required_if: conditions, 1| array',
            'products.*' => 'required_if: conditions, 1| exists:product,uuid',
        ];
    }
}
