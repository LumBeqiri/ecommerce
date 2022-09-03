<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Models\Order;

class OrderSellerController extends ApiController
{
    /**
     * @param Order $order
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Order $order){
        $sellers = $order->products()->with('seller')
        ->get()
        ->pluck('seller');

        return $this->showAll($sellers);
    }
}
