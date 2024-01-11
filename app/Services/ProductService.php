<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Support\Arr;

class ProductService
{
    public function createProduct($data): Product
    {
        $vendor = Vendor::where('user_id', auth()->user()->id)->firstOrFail();

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
