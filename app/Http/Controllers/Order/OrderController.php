<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Models\Order;

class OrderController extends ApiController
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $orders = Order::all();
        return $this->showAll($orders);
    }

    /**
     * @param Order $order
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order){
        return $this->showOne($order);
    }
}
