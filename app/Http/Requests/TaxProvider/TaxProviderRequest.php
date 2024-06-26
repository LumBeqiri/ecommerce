<?php

namespace App\Http\Requests\TaxProvider;

use Illuminate\Foundation\Http\FormRequest;

class TaxProviderRequest extends FormRequest
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
            'tax_provider' => 'required|string|max:255|unique:tax_providers,tax_provider',
            'is_installed' => 'boolean',
        ];
    }
}
