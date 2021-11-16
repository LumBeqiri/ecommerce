<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderSellerController extends ApiController
{
    public function index(Order $order){
        $sellers = $order->products()->with('seller')
        ->get()
        ->pluck('seller')
    ;


        return $this->showAll($sellers);
    }
}
