<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\Buyer;
use Illuminate\Http\JsonResponse;

class BuyerSellerController extends ApiController
{
    public function index(Buyer $buyer): JsonResponse
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
