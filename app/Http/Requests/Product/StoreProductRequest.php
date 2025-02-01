<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
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
            'categories' => 'required|array',
            'categories.*' => 'required|string|max:255|exists:categories,ulid',
            'status' => 'required|string|in:'.Product::UNAVAILABLE_PRODUCT.','.Product::AVAILABLE_PRODUCT,
            'publish_status' => 'required|string|in:'.Product::DRAFT.','.Product::PUBLISHED,
            'vendor_id' => 'nullable|numeric',
            'origin_country_id' => 'required|numeric|exists:countries,id',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        /** @var User $user */
        $user = auth()->user();
        
        if ($user->staff) {
            $vendorId = $user->staff->vendor;
        } elseif ($user->hasRole('vendor')) {
            $vendorId = Vendor::where('user_id', $user->id)->firstOrFail()->id;
        }else{
            $vendorId = Vendor::first()->id;
        }

        $this->merge([
            'vendor_id' => $vendorId,
        ]);

    }
}
