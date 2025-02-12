<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email'             => 'required|email',
            'password'          => 'required',
            'cart_items'        => 'sometimes|array',
            'cart_items.*.variant_id' => 'required|string',  // Each item must have a variant_id
            'cart_items.*.quantity'   => 'required|integer|min:1',  // and a quantity of at least 1
        ];
    }

}
