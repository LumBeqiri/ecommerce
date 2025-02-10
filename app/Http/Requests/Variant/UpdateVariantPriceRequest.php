<?php

namespace App\Http\Requests\Variant;

use App\Models\Region;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVariantPriceRequest extends FormRequest
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
            'region_id' => 'required',
            'price' => 'required|integer|min:1',
            'min_quantity' => 'sometimes|integer|min:1',
            'max_quantity' => 'sometimes|integer|min:1|gte:min_quantity',
        ];
    }

    protected function prepareForValidation()
    {

        $this->merge([
            'region_id' => Region::where('ulid', $this->region_id)->firstOrFail()->id,
        ]);
    }
}
