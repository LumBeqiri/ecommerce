<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Cart;
use App\Models\Buyer;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OrderResource;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;

class BuyerOrderController extends ApiController
{
    public function index(Buyer $buyer): JsonResponse
    {
        $orders = $buyer->orders;

        return $this->showAll(OrderResource::collection($orders));
    }

    public function store(StoreOrderRequest $request, Cart $cart)
    {
        $data = $request->validated();
        $data['buyer_id'] = auth()->id();

        Order::create($data);

        
    }
}
