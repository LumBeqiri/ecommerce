<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;

class CategorySellerController extends ApiController
{
    /**
     * @param Category $category
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category){
        $sellers = $category->products()
        ->with('seller')
        ->get()
        ->pluck('seller')
        ->unique('id')
        ->values();

        return $this->showAll($sellers);
    }
}
