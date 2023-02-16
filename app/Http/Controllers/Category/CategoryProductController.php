<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;

class CategoryProductController extends ApiController
{
    /**
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $products = $category->products;

        return $this->showAll(ProductResource::collection($products));
    }

    /**
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function subcats(Category $category)
    {
        $subs = $category->subcategory()->get();

        return $this->showAll(CategoryResource::collection($subs));
    }
}
