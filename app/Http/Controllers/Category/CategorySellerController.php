<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategorySellerController extends ApiController
{
    public function index(Category $category): JsonResponse
    {
        $sellers = $category->products()
        ->with('seller')
        ->get()
        ->pluck('seller')
        ->unique('id')
        ->values();

        return $this->showAll(UserResource::collection($sellers));
    }
}
