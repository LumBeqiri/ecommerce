<?php

namespace App\Http\Controllers\Public\Product;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductCategoryController extends ApiController
{
    public function index(Product $product): JsonResponse
    {
        $categories = $product->categories()->get();

        return $this->showAll(CategoryResource::collection($categories));
    }

}
