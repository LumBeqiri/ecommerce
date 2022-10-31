<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Models\Category;


class CategoryBuyerController extends ApiController
{
    /**
     * @param Category $category
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category){
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
