<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Models\Country;
use App\Models\Product;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends ApiController
{
    public function index(LocationService $locationService)
    {
        $country_name = $locationService->getCountry();

        $region_id = Country::select('region_id')->where('name', 'LIKE', '%'.$country_name.'%')->value('region_id');

        $products = Product::with(['variant_prices' => fn ($query) => $query->where('region_id', $region_id)])
        ->get();

        return $this->showAll(ProductResource::collection($products));
    }

    public function show(Product $product): JsonResponse
    {
        $product = QueryBuilder::for(Product::class)
            ->where('uuid', $product->uuid)
            ->allowedIncludes(['variants'])
            ->first();

        // dd($product);

        return $this->showOne(new ProductResource($product));
    }
}
