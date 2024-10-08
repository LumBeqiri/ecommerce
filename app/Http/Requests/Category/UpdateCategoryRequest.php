<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|max:255|string',
            'description' => 'sometimes|max:500|string',
            'slug' => 'sometimes|unique:categories,slug',
            'parent_id' => 'sometimes|exists:categories,ulid',
        ];
    }
}
