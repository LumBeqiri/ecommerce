<?php

namespace App\Http\Requests\Discount;

use App\Rules\UniqueDiscountConditionModelType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiscountConditionRequest extends FormRequest
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
            'operator' => 'required|in:in,not_in',
            'model_type' => ['required', 'string', Rule::in(['product', 'customer_group']), new UniqueDiscountConditionModelType],
            'products' => 'array',
            'products.*' => 'exists:products,uuid',
            'customer_groups' => 'array',
            'customer_groups.*' => 'exists:customer_groups,uuid',
            'metadata' => 'sometimes|json',

        ];
    }
}
