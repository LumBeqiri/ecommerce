<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends ApiController
{
    public function index(): JsonResponse
    {
        $orders = Order::all();

        return $this->showAll(OrderResource::collection($orders));
    }

    public function show(Order $order): JsonResponse
    {
        return $this->showOne(new OrderResource($order));
    }
}
