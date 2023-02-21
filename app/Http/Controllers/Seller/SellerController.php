<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
{
    public function index() : JsonResponse
    {
        $sellers = Seller::has('products')->get();

        return $this->showAll(UserResource::collection($sellers));
    }

    public function show(Seller $seller) : JsonResponse
    { 
        return $this->showOne(new UserResource($seller));
    }
}
