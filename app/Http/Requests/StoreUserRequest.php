<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        if ($this->getMethod() == 'POST') {
            return [
                'name' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'zip' => 'required|regex:/\b\d{5}\b/',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
            ];
        }

        return [
            'name' => 'string',
            'city' => 'string',
            'state' => 'string',
            'zip' => 'regex:/\b\d{5}\b/',
            // 'email'=> 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'min:6|confirmed',
        ];
    }
}
