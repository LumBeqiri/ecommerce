<?php

namespace App\Services;

use App\Data\ProductData;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Arr;

class ProductService
{
    public function createProduct(ProductData $productData): Product
    {
        $categoriesulids = $productData->categories;

        $categoriesIds = Category::whereIn('ulid', $categoriesulids)
            ->pluck('id')
            ->all();

        $product = Product::create(
            Arr::except($productData->all(), 'categories')
        );

        Variant::create([
            'variant_name' => $productData->product_name,
            'product_id' => $product->id,
        ]);

        $product->categories()->sync($categoriesIds);

        return $product->load('variants');
    }

    public function updateProduct(Product $product, $data): Product
    {
        $updateProductData = Arr::except($data, 'categories');
        $product->fill($updateProductData);

        if (Arr::has($data, 'categories')) {
            $categories = Category::all()->whereIn('ulid', $data['categories'])->pluck('id');
            $product->categories()->sync($categories);
        }

        $product->save();

        return $product;
    }

    public function syncProductCategories(Product $product, array $categoriesUlids): Product
    {

        $categories = Category::whereIn('ulid', $categoriesUlids)->pluck('id');
        $product->categories()->sync($categories);

        $product->save();

        return $product;
    }

    public function deleteProductCategories(Product $product): Product
    {
        $product->categories()->detach();

        return $product;
    }
}
