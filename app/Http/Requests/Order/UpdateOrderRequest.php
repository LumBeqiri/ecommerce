<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'shipping_name' => 'string|max:255',
            'shipping_address' => 'string|max:255',
            'shipping_city' => 'string|max:255',
            'shipping_country' => 'integer|exists:countries,id',
            'order_email' => 'email',
            'order_phone' => 'string',
        ];
    }
}
