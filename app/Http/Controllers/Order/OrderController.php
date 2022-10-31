<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrderController extends ApiController
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $orders = Order::all();
        return $this->showAll(OrderResource::collection($orders));
    }

    /**
     * @param Order $order
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order){
        return $this->showOne(new OrderResource($order));
    }
}
