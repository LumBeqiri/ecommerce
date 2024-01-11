<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Variant\StoreVariantRequest;
use App\Http\Requests\Variant\UpdateVariantRequest;
use App\Http\Resources\VariantResource;
use App\Models\Variant;
use App\Services\VariantService;
use Illuminate\Http\JsonResponse;

class VendorVariantAttributeController extends ApiController
{
    public function index(Variant $variant): JsonResponse
    {
        $this->authorize('view', $variant);

        return $this->showAll(new VariantResource($variant->load('attributes')));
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

    public function destroy(Variant $variant): JsonResponse
    {
        $this->authorize('delete', $variant);

        $variant->delete();

        return $this->showOne(new VariantResource($variant));
    }

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
