<?php

namespace App\Http\Requests;

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
        $max_images = 5;
        
        if($this->getMethod()== 'POST'){
          return [
            'sku' => 'required',
            'price' => 'required|numeric',
            'short_desc' => 'string|max:256',
            'long_desc' => 'string| max:900',
            'stock' => 'required|integer|min:1',
            'product_id' => 'in:products',
            'status' => 'in:' . Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT,
            'images' => 'max:' . $max_images,
            'images.*' => 'mimes:jpeg,jpg,png|max:2000'
          ];
        }else{
          return [
            'sku' => 'required',
            'price' => 'required|numeric',
            'short_desc' => 'string|max:256',
            'long_desc' => 'string| max:900',
            'stock' => 'required|integer|min:1',
            'product_id' => 'in:products',
            'status' => 'in:' . Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT,
            'images' => 'max:' . $max_images,
            'images.*' => 'mimes:jpeg,jpg,png|max:2000'
          ];
        }


    }

    public function messages() {
        $max_images = 5;
        return [
          'images.*.max' => 'Image size should be less than 2mb',
          'coverImage.*.mimes' => 'Only jpeg, png, jpg files are allowed.',
          'images.max' => 'Only ' . $max_images . ' images per product are allowed'
        ];
      }
}