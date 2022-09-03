<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;


class CategoryOrderController extends ApiController
{
    /**
     * @param Category $category
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category){
        $orders = $category->products()
        ->whereHas('orders')
        ->get()
        ->values();

        return $this->showAll($orders);
    }
}
