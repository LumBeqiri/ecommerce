<?php

namespace App\Http\Controllers\Order;

use App\Models\Order;
use App\Http\Controllers\ApiController;

class OrderCategoryController extends ApiController
{
    /**
     * @param Order $order
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Order $order)
    {
        $categories = $order->products()->with('categories')
        ->get()
        ->pluck('categories')
        ->collapse();
        
        return $this->showAll($categories);
    }
}
