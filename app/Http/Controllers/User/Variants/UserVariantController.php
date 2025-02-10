<?php

namespace App\Http\Controllers\User\Variants;

use App\Data\VariantData;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Variant\StoreVariantRequest;
use App\Http\Requests\Variant\UpdateVariantRequest;
use App\Http\Resources\VariantResource;
use App\Models\Product;
use App\Models\Variant;
use App\Services\VariantService;
use Illuminate\Http\JsonResponse;

class UserVariantController extends ApiController
{
    public function store(StoreVariantRequest $request, VariantService $variantService): JsonResponse
    {

        $product = Product::find($request->validated()['product_id']);

        $this->authorize('update', $product);

        $variantData = VariantData::from($request->validated());

        $newVariant = $variantService->createVariant($variantData);

        return $this->showOne(new VariantResource($newVariant));
    }

    public function show(Variant $variant) : JsonResponse
    {
        return $this->showOne(new VariantResource($variant->load(['variant_prices', 'attributes'])));
    }

    public function update(UpdateVariantRequest $request, Variant $variant): JsonResponse
    {
        $this->authorize('update', $variant);

        $variantUpdateData = VariantData::from(array_merge($variant->toArray(), $request->validated()));

        $variant->update($variantUpdateData->toArray());

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
