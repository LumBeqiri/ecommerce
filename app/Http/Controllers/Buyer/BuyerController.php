<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{

    public function index() : JsonResponse
    {
        $buyers = Buyer::has('orders')->get();

        return $this->showAll(UserResource::collection($buyers));
    }

    public function show(Buyer $buyer) : JsonResponse
    {
        return $this->showOne(new UserResource($buyer));
    }
}
