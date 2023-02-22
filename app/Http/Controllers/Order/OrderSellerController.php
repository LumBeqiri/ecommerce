<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderSellerController extends ApiController
{

    public function index(Order $order) : JsonResponse
    {
        $sellers = $order->products()->with('seller')
        ->get()
        ->pluck('seller');

        return $this->showAll(UserResource::collection($sellers));
    }
}
