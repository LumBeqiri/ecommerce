<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function createProduct($data): Product
    {

        /** @var User $user */
        $user = Auth::user();
        
        if($user->staff){
            $vendor = auth()->user()->staff->vendor_id;
        }

        if($user->hasRole('vendor')){
            $vendor = Vendor::where('user_id', auth()->user()->id)->firstOrFail();
        }

        $categoriesUuids = $data['categories'];

        $categories = Category::whereIn('uuid', $categoriesUuids)->get();

        $product = Product::create(
            Arr::except($data, 'categories') + ['vendor_id' => $vendor->id]
        );

        $product->categories()->sync($categories);

        return $product;
    }

    public function updateProduct(Product $product, $data): Product
    {
        $updateProductData = Arr::except($data, 'categories');
        $product->fill($updateProductData);

        if (Arr::has($data, 'categories')) {
            $categories = Category::all()->whereIn('uuid', $data['categories'])->pluck('id');
            $product->categories()->sync($categories);
        }

        $product->save();

        return $product;
    }
}
