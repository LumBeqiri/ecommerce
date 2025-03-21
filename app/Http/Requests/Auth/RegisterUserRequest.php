<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'city' => 'required',
            'country_id' => 'required',
            'zip' => 'required|regex:/\b\d{5}\b/',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users',
            'shipping_address' => 'string|max:255',
            'password' => 'required|min:8|confirmed',
        ];
    }
}
