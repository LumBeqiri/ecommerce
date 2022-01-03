<?php

namespace App\Http\Controllers\Order;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class OrderCategoryController extends ApiController
{
    public function index(Order $order)
    {
        $categories = $order->products()->with('categories')
        ->get()
        ->pluck('categories')
        ->collapse();
        
        return $this->showAll($categories);
    }
}
