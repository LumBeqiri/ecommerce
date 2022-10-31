<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;

class ProductBuyerController extends ApiController
{
    /**
     * @param Product $product
     * 
     * @return \Illuminate\Http\Response
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
