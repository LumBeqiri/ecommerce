<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;

class CategoryProductController extends ApiController
{

    public function index(Category $category) : JsonResponse
    {
        $products = $category->products;

        return $this->showAll(ProductResource::collection($products));
    }


    public function subcats(Category $category) : JsonResponse
    {
        $subs = $category->subcategory()->get();

        return $this->showAll(CategoryResource::collection($subs));
    }
}
