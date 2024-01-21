<?php

namespace App\Http\Requests\Variant;

use Illuminate\Foundation\Http\FormRequest;

class StoreVariantPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'region_id' => 'required|exists:regions,uuid',
            'price' => 'required|integer|min:1',
            'min_quantity' => 'required|integer|min:1',
            'max_quantity' => 'required|integer|min:1|gte:min_quantity',
        ];
    }
}
