<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Services\ProductService;
use App\Services\VariantService;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VariantResource;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Variant\StoreVariantRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Variant\UpdateVariantRequest;

class VendorVariantController extends ApiController
{
    public function index(Product $product): JsonResponse
    {
        $this->authorize('view', $product);

        $variants = $product->variants()->get();

        return $this->showAll(VariantResource::collection($variants));
    }

    public function store(StoreVariantRequest $request, VariantService $variantService): JsonResponse
    {
        $newVariant = $variantService->createVariant($request->validated());

        return $this->showOne(new VariantResource($newVariant));
    }

    public function update(UpdateVariantRequest $request, Variant $variant): JsonResponse
    {
        $this->authorize('update', $variant);
        
        $variant->fill($request->validated());
        $variant->save();

        return $this->showOne(new VariantResource($variant->load('variant_prices')));
    }

    // public function destroy(Variant $variant): JsonResponse
    // {
    //     $this->authorize('delete', $variant);

    //     $variant->delete();

    //     return $this->showOne(new VariantResource($variant));
    // }

    // /**
    //  * @param  array<string, mixed>  $variant_prices
    //  */
    // private function createVariantPrice(array $variant_prices, Variant $newVariant): void
    // {
    //     foreach ($variant_prices as $variant_price) {
    //         $variant_price['region_id'] = Region::where('uuid', $variant_price['region_id'])->firstOrFail()->id;
    //         $variant_price['variant_id'] = $newVariant->id;
    //         VariantPrice::create($variant_price);
    //     }
    // }
}
