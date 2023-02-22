<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderCategoryController extends ApiController
{

    public function index(Order $order) : JsonResponse
    {
        $categories = $order->products()->with('categories')
        ->get()
        ->pluck('categories')
        ->collapse();

        return $this->showAll(CategoryResource::collection($categories));
    }
}
