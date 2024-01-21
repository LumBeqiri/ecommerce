<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Variant\StoreVariantRequest;
use App\Http\Requests\Variant\UpdateVariantRequest;
use App\Http\Resources\VariantResource;
use App\Models\Variant;
use App\Services\VariantService;
use Illuminate\Http\JsonResponse;

class VendorVariantController extends ApiController
{
    public function show(Variant $variant)
    {
        return $this->showOne(new VariantResource($variant->load(['variant_prices', 'attributes'])));
    }

    public function store(StoreVariantRequest $request, VariantService $variantService): JsonResponse
    {
        $newVariant = $variantService->createVariant($request->validated());

        return $this->showOne(new VariantResource($newVariant));
    }

    public function update(UpdateVariantRequest $request, Variant $variant): JsonResponse
    {
        $this->authorize('update', $variant);

        $variantUpdateData = $request->validated();

        $variant->fill($variantUpdateData);
        $variant->save();

        return $this->showOne(new VariantResource($variant->load('variant_prices')));
    }

    public function destroy(Variant $variant): JsonResponse
    {
        $this->authorize('delete', $variant);

        $message = '';
        $variant->delete() ? ($message = 'Variant deleted successfully!') : ($message = 'Variant was not deleted!');

        return $this->showMessage($message);
    }

}
