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

        $max_images = 5;
    
          return [
            'sku' => 'string|max:255|required|unique:variants',
            'short_desc' => 'string|max:256',
            'long_desc' => 'string| max:900',
            'stock' => 'required|integer|min:1',
            'attrs' => 'array',
            'attrs.*' => 'required|max:150|string|exists:attributes,uuid',
            'product_prices' => 'array',
            'product_prices.*.region_id' => 'required|exists:regions,uuid',
            'product_prices.*.currency_id' => 'required|exists:currencies,id',
            'product_prices.*.price' => 'required|integer|min:1',
            'status' => 'in:' . Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT,
            'medias' => 'max:' . $max_images,
            'medias.*' => 'mimes:jpeg,jpg,png|max:2000'
          ];
     


    }

    public function messages() {
        $max_images = 5;
        return [
            'medias.*.max' => 'media size should be less than 2mb',
            'coverImage.*.mimes' => 'Only jpeg, png, jpg files are allowed.',
            'medias.max' => 'Only ' . $max_images . ' files per product are allowed'
        ];
      }
}
