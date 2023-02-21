<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Models\Buyer;
use Illuminate\Http\JsonResponse;

class BuyerProductController extends ApiController
{
    public function index(Buyer $buyer): JsonResponse
    {
        $products = $buyer->orders()->with('products')
        ->get()
        ->pluck('products')
        ->collapse();

        return $this->showAll(ProductResource::collection($products));
    }
}
