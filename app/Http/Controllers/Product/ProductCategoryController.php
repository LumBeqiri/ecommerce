<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Product;


class ProductCategoryController extends ApiController
{
    public function index(Product $product){
        $categories = $product->categories()->get();

        return $this->showAll($categories);
    }

    public function deleteCategories(Product $product){
        $product->categories()->detach();
    }
}
