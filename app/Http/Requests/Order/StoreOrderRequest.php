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
        return [
            'shipping_name' => 'required|string|max:191',
            'shipping_address' => 'required|string|max:191',
            'shipping_city' => 'required|string|max:191',
            'shipping_country' => 'required|string|max:191',
            'order_tax' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'order_date' => 'required|date',
            'order_shipped' => 'nullable|string|max:191',
            'order_email' => 'nullable|email|max:191',
            'order_phone' => 'nullable|string|max:191',
        ];
    }
}
