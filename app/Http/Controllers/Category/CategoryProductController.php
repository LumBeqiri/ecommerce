<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;

class CategoryProductController extends ApiController
{
    /**
     * @param Category $category
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category){
        $products = $category->products;

        return $this->showAll($products);
    }

    /**
     * @param Category $category
     * 
     * @return \Illuminate\Http\Response
     */
    public function subcats(Category $category){
       $subs = $category->subcategory()->get();
       
       return $this->showAll($subs);
    }
}
