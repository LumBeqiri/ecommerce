<?php

namespace App\Http\Requests\Discount;

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
            'allocation' => 'required_if:discount_type,'.DiscountRuleTypes::FIXED_AMOUNT.'|in:'.DiscountAllocationTypes::ITEM_SPICIFIC.','.DiscountAllocationTypes::TOTAL_AMOUNT,
            'value' => 'required_if:discount_type,'.DiscountRuleTypes::PERCENTAGE.'|numeric',
            'region' => 'exists:regions,uuid',
            'code' => 'sometimes|string',
            'description' => 'sometimes|string|max:255',
            'is_dynamic' => 'nullable|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'usage_limit' => 'nullable|numeric',
        ];
    }
}
