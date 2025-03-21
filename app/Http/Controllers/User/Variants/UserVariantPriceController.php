<?php

namespace App\Http\Controllers\User\Variants;

use App\Data\VariantPriceData;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Variant\StoreVariantPriceRequest;
use App\Http\Requests\Variant\UpdateVariantPriceRequest;
use App\Http\Resources\VariantResource;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Services\VariantService;
use Exception;
use Illuminate\Http\JsonResponse;

class UserVariantPriceController extends ApiController
{
    public function store(StoreVariantPriceRequest $request, Variant $variant, VariantService $variantService): JsonResponse
    {
        $this->authorize('update', $variant);

        try {
            $variantPriceData = VariantPriceData::from($request->validated());
            $variantService->addVariantPrice($variant, $variantPriceData);

            return $this->showOne(new VariantResource($variant->load(['variant_prices'])));

        } catch (Exception $ex) {
            return $this->showError(message: $ex->getMessage(), code: $ex->getCode());
        }

    }

    public function update(UpdateVariantPriceRequest $request, Variant $variant, VariantPrice $variantPrice, VariantService $variantService): JsonResponse
    {
        $this->authorize('update', $variant);
        try {
            $variantPriceData = VariantPriceData::from($request->validated());

            $variantService->updateVariantPrice($variant, $variantPrice, $variantPriceData);

            return $this->showOne(new VariantResource($variant->load('variant_prices')));
        } catch (Exception $ex) {
            return $this->respondInvalidQuery(message: $ex->getMessage(), code: $ex->getCode());
        }

    }

    public function destroy(Variant $variant, VariantPrice $variantPrice): JsonResponse
    {
        $this->authorize('delete', $variant);

        abort_if($variant->id != $variantPrice->variant_id, 422, "Pricing doesn't belong to the product variant");

        $variantPrice->delete();

        return $this->showMessage('Variant Pricing deleted successfully!');
    }
}
