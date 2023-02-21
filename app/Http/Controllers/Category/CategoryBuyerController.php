<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryBuyerController extends ApiController
{
    public function index(Category $category): JsonResponse
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
