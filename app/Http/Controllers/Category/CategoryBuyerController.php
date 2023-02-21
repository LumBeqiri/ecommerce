<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{

    public function index(Category $category) : JsonResponse
    {
        $buyers = $category->products()->whereHas('orders')
        ->with('orders.buyer')->get()
        ->pluck('orders')
        ->collapse()
        ->pluck('buyer')
        ->unique('id')
        ->values();

        return $this->showAll(UserResource::collection($buyers));
    }
}
