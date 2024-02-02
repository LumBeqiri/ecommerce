<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStaffRequest extends FormRequest
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
        return [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'position' => 'required|string|max:191',
            'phone' => 'required|string|max:191',
            'city' => 'required|string|max:191',
            'status' => 'required|string|max:191',
            'notes' => 'nullable|string|max:500',
            'address' => 'required|string|max:191',
            'vendor_id' => 'required|exists:vendors,id',
            'country_id' => 'required|exists:countries,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'role_id' => 'required|exists:roles,id',
        ];
    }
}
