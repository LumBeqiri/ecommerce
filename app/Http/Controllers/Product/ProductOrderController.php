<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductOrderController extends ApiController
{
    public function index(Product $product): JsonResponse
    {
        $orders = $product->orders;

        return $this->showAll(OrderResource::collection($orders));
    }
}
