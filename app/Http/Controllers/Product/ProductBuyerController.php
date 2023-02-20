<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\Product;

class ProductBuyerController extends ApiController
{
    /**
     * @param  Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Product $product)
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
