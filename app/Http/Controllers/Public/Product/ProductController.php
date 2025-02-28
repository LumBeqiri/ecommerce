<?php

namespace App\Http\Controllers\Public\Product;

use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends ApiController
{
    public function index(): JsonResponse
    {

        $region_id = Region::first()->id;

        $products = Product::whereHas('variant_prices', function ($query) use ($region_id) {
            $query->where('region_id', $region_id);
        })
            ->with([
                'vendor.user',
                'variants.media',
                'variant_prices' => function ($query) use ($region_id) {
                    $query->where('region_id', $region_id)->with(['currency', 'region']);
                },
            ])
            ->paginate(10);

        return $this->showAll(ProductResource::collection($products));
    }

    public function show(Product $product): JsonResponse
    {
        /** @phpstan-ignore-next-line */
        $product = QueryBuilder::for(Product::class)
            ->where('ulid', $product->ulid)
            ->allowedIncludes(['variant_prices', 'variants'])
            ->first();

        return $this->showOne(new ProductResource($product));
    }
}
