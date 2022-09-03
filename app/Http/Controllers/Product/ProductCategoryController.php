<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;

use App\Models\Product;


class ProductCategoryController extends ApiController
{
    /**
     * @param Product $product
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product){
        $categories = $product->categories()->get();

        return $this->showAll($categories);
    }

    /**
     * @param Product $product
     * 
     * @return void
     */
    public function deleteCategories(Product $product){
        $product->categories()->detach();
    }
}
