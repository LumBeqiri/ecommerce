<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductBuyerController extends ApiController
{
    public function index(Product $product): JsonResponse
    {
        $buyers = $product->orders()
        ->with('buyer')
        ->get()
        ->pluck('buyer')
        ->unique('id')
        ->values();

        return $this->showAll(UserResource::collection($buyers));
    }
}
