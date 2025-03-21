<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
        $rules = [
            'shipping_name' => 'nullable|string|max:191',
            'different_shipping_address' => 'boolean',
            'shipping_address' => 'sometimes|string|max:191',
            'shipping_city' => 'required|string|max:191',
            'shipping_country_id' => 'required|integer|exists:countries,id',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'ordered_at' => 'required|date',
            'order_email' => 'required|email|max:191',
            'order_phone' => 'required|string|max:191',
        ];

        if ($this->input('different_shipping_address')) {
            $rules['shipping_address'] = 'required|string|max:191';
        }

        return $rules;
    }
}
