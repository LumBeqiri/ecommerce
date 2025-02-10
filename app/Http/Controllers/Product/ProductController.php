<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends ApiController
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $region_id = $user->buyer->country->region_id;

        $products = Product::whereHas('variant_prices', function ($query) use ($region_id) {
            $query->where('region_id', $region_id);
        })
            ->with([
                'vendor.user',
                'variants.medias',
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
