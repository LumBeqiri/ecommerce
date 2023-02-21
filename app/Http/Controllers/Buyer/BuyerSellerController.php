<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{

    public function index(Buyer $buyer) : JsonResponse
    {
        $sellers = $buyer->orders()->with('products.seller')->get()
        ->pluck('products')
        ->collapse()
        ->pluck('seller')
        ->unique('id')
        ->values();

        return $this->showAll(UserResource::collection($sellers));
    }
}
