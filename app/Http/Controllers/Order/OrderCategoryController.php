<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryResource;
use App\Models\Order;

class OrderCategoryController extends ApiController
{
    /**
     * @return Illuminate\Http\JsonResponse.
     */
    public function index(Order $order)
    {
        $categories = $order->products()->with('categories')
        ->get()
        ->pluck('categories')
        ->collapse();

        return $this->showAll(CategoryResource::collection($categories));
    }
}
