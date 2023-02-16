<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\Seller;

class SellerController extends ApiController
{
    public function index()
    {
        $sellers = Seller::has('products')->get();

        return $this->showAll(UserResource::collection($sellers));
    }

    public function show(Seller $seller)
    {
        return $this->showOne(new UserResource($seller));
    }
}
