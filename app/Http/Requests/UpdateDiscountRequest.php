<?php

namespace App\Http\Requests;

use App\values\DiscountAllocationTypes;
use App\values\DiscountRuleTypes;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountRequest extends FormRequest
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
            'discount_type' => 'sometimes|in:'.DiscountRuleTypes::FIXED.','.DiscountRuleTypes::FREE_SHIPPING.','.DiscountRuleTypes::PERCENTAGE,
            'allocation' => 'required_if:discount_type,'.DiscountRuleTypes::FIXED.'|in:'.DiscountAllocationTypes::ITEM_SPICIFIC.','.DiscountAllocationTypes::TOTAL_AMOUNT,
            'percentage' => 'required_if:discount_type,'.DiscountRuleTypes::PERCENTAGE.'|numeric',
            'amount' => 'required_if:discount_type,'.DiscountRuleTypes::FIXED.'|numeric',
            'regions' => 'array',
            'regions.*' => 'exists:regions,uuid',
            'code' => 'sometimes|string',
            'description' => 'sometimes|string|max:255',
            'is_dynamic' => 'nullable|boolean',
            'starts_at' => 'nullable|timestamp',
            'ends_at' => 'nullable|timestamp',
            'usage_limit' => 'nullable|number',
        ];
    }
}
