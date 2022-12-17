<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVariantRequest extends FormRequest
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
        $max_images = 5;

        return [
            'sku' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'short_desc' => 'sometimes|string|max:256',
            'long_desc' => 'sometimes|string| max:900',
            'stock' => 'sometimes|required|integer|min:1',
            'attrs' => 'array',
            'attrs.*' => 'required|max:150|string|exists:attributes,uuid',
            'product_id' => 'in:products',
            'status' => 'in:' . Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT,
            'medias' => 'max:' . $max_images,
            'medias.*' => 'mimes:jpeg,jpg,png|max:2000'
        ];
    }
}
