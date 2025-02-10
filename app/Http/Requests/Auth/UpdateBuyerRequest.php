<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBuyerRequest extends FormRequest
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
            'city' => 'sometimes|string|max:191',
            'country_id' => 'sometimes|exists:countries,id',
            'zip' => 'sometimes|integer',
            'shipping_address' => 'sometimes|string|max:191',
            'phone' => 'sometimes|string|max:25',
        ];
    }
}
