<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    public function index(){
        $orders = Order::all();
        return $this->showAll($orders);
    }

    public function show(Order $order){
        return $this->showOne($order);
    }
}
