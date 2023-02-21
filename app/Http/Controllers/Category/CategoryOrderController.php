<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OrderResource;
use App\Http\Controllers\ApiController;

class CategoryOrderController extends ApiController
{

    public function index(Category $category) : JsonResponse
    {
        $orders = $category->products()
        ->whereHas('orders')
        ->get()
        ->values();

        return $this->showAll(OrderResource::collection($orders));
    }
}
