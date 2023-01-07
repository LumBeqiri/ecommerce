<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegionRequest extends FormRequest
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
            'title' => 'string|max:255',
            'currency_id' => 'exists:currencies,id',
            'tax_rate' => 'integer|max:100',
            'tax_code' => 'string|max:255',
            'tax_provider_id' => 'exists:tax_providers,id',
            'countries' => 'array',
            // 'countries.*' => 'integer|exists:countries,id'
        ];
    }
}
