<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryProductController extends ApiController
{
    public function index(Category $category): JsonResponse
    {
        $products = $category->products;

        return $this->showAll(ProductResource::collection($products));
    }

    public function subcats(Category $category): JsonResponse
    {
        $subs = $category->subcategory()->get();

        return $this->showAll(CategoryResource::collection($subs));
    }
}
