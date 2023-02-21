<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OrderResource;
use App\Http\Controllers\ApiController;

class ProductOrderController extends ApiController
{
    public function index(Product $product) : JsonResponse
    {
        $orders = $product->orders;

        return $this->showAll(OrderResource::collection($orders));
    }
}
