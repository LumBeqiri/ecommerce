<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'ship_name' => 'nullable|string',
            'ship_address' => 'required|string',
            'ship_city' => 'required|string',
            'ship_country' => 'required|string',
            'order_tax' => 'nullable|numeric',
            'total' => 'required|numeric',
            'order_date' => 'required|date',
            'order_shipped' => 'required|boolean',
            'order_email' => 'required|email',
            'order_phone' => 'required|string',
        ];
    }
}
