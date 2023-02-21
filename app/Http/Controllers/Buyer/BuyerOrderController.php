<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OrderResource;
use App\Http\Controllers\ApiController;

class BuyerOrderController extends ApiController
{

    public function index(Buyer $buyer) : JsonResponse
    {
        $orders = $buyer->orders;

        return $this->showAll(OrderResource::collection($orders));
    }
}
