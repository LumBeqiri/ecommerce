<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreVariantRequest extends FormRequest
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
            //rules for variant model
            'variant_name' => 'required|string|max:255',
            'sku' => 'string|max:255|required|unique:variants',
            'barcode' => 'nullable|string|max:255|unique:variants',
            'ean' => 'nullable|string|max:255|unique:variants',
            'upc' => 'nullable|string|max:255|unique:variants',
            'variant_short_description' => 'string|max:255',
            'variant_long_description' => 'string| max:255',
            'stock' => 'required|integer|min:0',
            'manage_inventory' => 'required|boolean',
            'status' => 'required|string|in:'.Product::UNAVAILABLE_PRODUCT.','.Product::AVAILABLE_PRODUCT,
            'publish_status' => 'required|string|in:'.Product::DRAFT.','.Product::PUBLISHED,
            'allow_backorder' => 'nullable|boolean',
            'material' => 'nullable|string|max:255',
            'weight' => 'nullable|integer|min:0',
            'length' => 'nullable|integer|min:0',
            'height' => 'nullable|integer|min:0',
            'width' => 'nullable|integer|min:0',
            //rules for product_prices
            'variant_prices' => 'required|array',
            'variant_prices.*.region_id' => 'required|exists:regions,uuid',
            'variant_prices.*.price' => 'required|integer|min:1',
        ];
    }
}
