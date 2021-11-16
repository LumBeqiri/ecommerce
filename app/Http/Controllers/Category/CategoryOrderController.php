<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryOrderController extends ApiController
{
    public function index(Category $category){
        $orders = $category->products()
        ->whereHas('orders')
        ->get()
        ->values();

        return $this->showAll($orders);
    }
}
