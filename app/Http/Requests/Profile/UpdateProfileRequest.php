<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $user = Auth::user();

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
        ];

        if ($user->vendor) {
            $rules = array_merge($rules, [
                'vendor_name' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'country_id' => 'required|integer|exists:countries,id',
                'approval_date' => 'nullable|date',
                'website' => 'nullable|url',
            ]);
        } elseif ($user->staff) {
            $rules = array_merge($rules, [
                'address' => 'nullable|string|max:255',
            ]);
        }

        return $rules;
    }
}
