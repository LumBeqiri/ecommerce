<?php

namespace App\Http\Requests\Variant;

use App\Models\Region;
use Illuminate\Foundation\Http\FormRequest;

class StoreVariantPriceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'region_id' => 'required|exists:regions,id',
            'price' => 'required|numeric|min:1',
            'currency_id' => 'required|numeric|exists:currencies,id',
            'min_quantity' => 'required|integer|min:1',
            'max_quantity' => 'required|integer|min:1|gte:min_quantity',
        ];
    }

    protected function prepareForValidation()
    {

        $this->merge([
            'region_id' => Region::where('ulid', $this->region_id)->first()->id,
        ]);
    }
}
