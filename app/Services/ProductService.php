<?php

namespace App\Services;

use App\Data\ProductData;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function createProduct(ProductData $productData): Product
    {
        $categoriesulids = $productData->categories;

        $categoriesIds = Category::whereIn('ulid', $categoriesulids)
            ->pluck('id')
            ->all();

        DB::beginTransaction();
        try {
            $product = Product::create(
                Arr::except($productData->all(), 'categories')
            );

            Variant::create([
                'variant_name' => $productData->product_name,
                'product_id' => $product->id,
            ]);

            $product->categories()->sync($categoriesIds);

            DB::commit();

            return $product->load('variants');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProduct(Product $product, ProductData $data): Product
    {
        DB::beginTransaction();

        try {
            $updateProductData = $data->except('categories');
            $product->fill($updateProductData->toArray());

            if ($data->categories) {
                $categories = Category::all()->whereIn('ulid', $data->categories)->pluck('id');
                $product->categories()->sync($categories);
            }

            $product->save();

            DB::commit();

            return $product->load('variants');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function deleteProductCategories(Product $product): Product
    {
        $product->categories()->detach();

        return $product;
    }
}
