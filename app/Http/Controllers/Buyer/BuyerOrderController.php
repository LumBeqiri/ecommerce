<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderResource;
use App\Models\Buyer;
use Illuminate\Http\JsonResponse;

class BuyerOrderController extends ApiController
{
    public function index(Buyer $buyer): JsonResponse
    {
        $orders = $buyer->orders;

        return $this->showAll(OrderResource::collection($orders));
    }
}
