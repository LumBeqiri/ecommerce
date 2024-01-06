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
            'status' => 'required|string|in:'.Product::UNAVAILABLE_PRODUCT.','.Product::AVAILABLE_PRODUCT,
            'publish_status' => 'required|string|in:'.Product::DRAFT.','.Product::PUBLISHED,
        ];
    }
}
