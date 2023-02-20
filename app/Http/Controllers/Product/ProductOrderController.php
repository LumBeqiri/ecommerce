<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderResource;
use App\Models\Product;

class ProductOrderController extends ApiController
{
    /**
     * @param Product $product
     * 
     * @return \Illuminate\Http\JsonResponse.
     */
    public function index(Product $product)
    {
        $orders = $product->orders;

        return $this->showAll(OrderResource::collection($orders));
    }
}
