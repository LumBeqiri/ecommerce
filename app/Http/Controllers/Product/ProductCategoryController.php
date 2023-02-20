<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryResource;
use App\Models\Product;

class ProductCategoryController extends ApiController
{
    /**
     * @param  Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Product $product)
    {
        $categories = $product->categories()->get();

        return $this->showAll(CategoryResource::collection($categories));
    }

    /**
     * @param  Product  $product
     * @return void
     */
    public function deleteCategories(Product $product)
    {
        $product->categories()->detach();
    }
}
