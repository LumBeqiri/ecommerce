<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;

class SellerController extends ApiController
{
    public function index(): JsonResponse
    {
        $sellers = Seller::has('products')->get();

        return $this->showAll(UserResource::collection($sellers));
    }

    public function show(Seller $seller): JsonResponse
    {
        return $this->showOne(new UserResource($seller));
    }
}
