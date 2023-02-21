<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;

class ProductController extends ApiController
{

    public function index() : JsonResponse
    {
        $products = Product::all();

        return $this->showAll(ProductResource::collection($products));
    }

    public function show(Product $product) : JsonResponse
    {
        $product = QueryBuilder::for(Product::class)
            ->with(['variants.medias'])
            ->where('uuid', $product->uuid)
            ->first();

        return $this->showOne(new ProductResource($product));
    }
}
