<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Controllers\ApiController;

class CategorySellerController extends ApiController
{

    public function index(Category $category) : JsonResponse
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
