<?php

namespace App\Http\Requests;

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
            'name' => 'required',
            'sku' => 'required',
            'price' => 'required|numeric',
            'weight' => 'numeric|nullable',
            'size' => 'string|nullable',
            'short_desc' => 'string|max:256',
            'long_desc' => 'string| max:900',
            'stock' => 'required|integer|min:1',
            'currency_id' => 'integer',
            'images' => 'max:2',
            'images.*' => 'mimes:jpeg,jpg,png|max:2000'

        ];
    }

    public function messages() {
        return [
          'images.*.max' => 'Image size should be less than 2mb',
          'coverImage.*.mimes' => 'Only jpeg, png, jpg files are allowed.',
          'images.max' => 'Only 2 images are allowed'
        ];
      }
}
