<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'product_name' => 'required|string|max:255',
            'product_short_description' => 'string|max:256',
            'product_long_description' => 'string| max:900',
            'categories' => 'required',
            'status' => 'required|string|in:'. Product::UNAVAILABLE_PRODUCT.','.Product::AVAILABLE_PRODUCT,
            'publish_status' => 'required|string|in:'. Product::DRAFT.','.Product::PUBLISHED,
            //rules for variant model
            'sku' => 'required|unique:variants',
            'barcode' => 'nullable|string|max:255|unique:variants',
            'ean' => 'nullable|string|max:255|unique:variants',
            'upc' => 'nullable|string|max:255|unique:variants',
            'stock' => 'required|integer|min:0',
            'variant_name' => 'string| max:50',
            'variant_short_description' => 'string|max:255',
            'variant_long_description' => 'string| max:255',
            'manage_inventory' => 'required|boolean',
            'allow_backorder' => 'nullable|boolean',
            'material' => 'nullable|string|max:255',
            'weight' => 'nullable|integer|min:0',
            'length' => 'nullable|integer|min:0',
            'height' => 'nullable|integer|min:0',
            'width' => 'nullable|integer|min:0',
            //rules for attributes
            'product_attributes' => 'array',
            'product_attributes.*.attribute_type' => 'required|string|max:150|unique:attributes,attribute_value',
            'product_attributes.*.attribute_value' => 'required|string|max:150|unique:attributes,attribute_value',
            //rules for variant_prices
            'variant_prices' => 'required|array',
            'variant_prices.*.region_id' => 'required|exists:regions,uuid',
            'variant_prices.*.price' => 'required|integer|min:0| max:100000000',
            'variant_prices.*.max_quantity' => 'nullable|integer',
            'variant_prices.*.min_quantity' => 'nullable|integer',
        ];
    }
}
