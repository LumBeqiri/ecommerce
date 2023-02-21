<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Controllers\ApiController;

class ProductBuyerController extends ApiController
{

    public function index(Product $product) : JsonResponse
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
