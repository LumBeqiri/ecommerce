<?php

namespace App\Http\Controllers\Product;

use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Services\LocationService;

class ProductController extends ApiController
{
    public function index(LocationService $locationService): JsonResponse
    {
        $products = Product::all();

        $country_name = $locationService->getCountry();

        $region_id = Country::select('region_id')->where('name', 'LIKE', '%'.$country_name.'%')->value('region_id');

        $products = Product::with(['seller', 'variant_prices' => function ($query) use ($region_id) {
            $query->where('region_id', $region_id);
        }])->get();

        return $this->showAll(ProductResource::collection($products));
    }

    public function show(Product $product): JsonResponse
    {
        $product = QueryBuilder::for(Product::class)
            ->with(['variants.medias'])
            ->where('uuid', $product->uuid)
            ->first();

        return $this->showOne(new ProductResource($product));
    }



}
