<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'shipping_name' => 'string',
            'shipping_address' => 'string',
            'shipping_city' => 'string',
            'shipping_country' => 'string',
            'order_email' => 'email',
            'order_phone' => 'string',
        ];
    }
}
