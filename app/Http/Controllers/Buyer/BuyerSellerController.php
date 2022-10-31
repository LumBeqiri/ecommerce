<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;

class BuyerSellerController extends ApiController
{
    /**
     * @param Buyer $buyer
     * 
     * @return  \Illuminate\Http\Response
     */
    public function index(Buyer $buyer){
        $sellers = $buyer->orders()->with('products.seller')->get()
        ->pluck('products')
        ->collapse()
        ->pluck('seller')
        ->unique('id')
        ->values();

        return $this->showAll(UserResource::collection($sellers));
    }
}
