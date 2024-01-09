<?php

namespace App\Http\Requests\Variant;

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
            'variant_name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|max:255',
            'variant_short_description' => 'sometimes|string|max:256',
            'variant_long_description' => 'sometimes|string| max:900',
            'stock' => 'sometimes|required|integer|min:1',
            'manage_inventory' => 'sometimes|boolean',
            // 'attributes' => 'array',
            // 'attributes.*' => 'required|max:150|string|exists:attributes,uuid',
            'product_id' => 'required|exists:products,uuid',
            'status' => 'in:'. Product::AVAILABLE_PRODUCT . ','.Product::UNAVAILABLE_PRODUCT,
            'publish_status' => 'in:'.Product::PUBLISHED.','.Product::DRAFT,
            // 'medias' => 'max:'.$max_images,
            // 'medias.*' => 'mimes:jpeg,jpg,png|max:2000',
        ];
    }
}
