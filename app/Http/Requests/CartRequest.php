<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
        if ($this->getMethod() == 'PUT') {
            return [
                'is_closed' => 'required|boolean',
            ];
        }

        return [
            'items' => 'array',
            'items.*.variant_id' => 'required|exists:variants,uuid',
            'items.*.count' => 'integer',
        ];
    }
}
