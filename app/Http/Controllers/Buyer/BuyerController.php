<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\Buyer;
use Illuminate\Http\JsonResponse;

class BuyerController extends ApiController
{
    public function index(): JsonResponse
    {
        $buyers = Buyer::has('orders')->get();

        return $this->showAll(UserResource::collection($buyers));
    }

    public function show(Buyer $buyer): JsonResponse
    {
        return $this->showOne(new UserResource($buyer));
    }
}
