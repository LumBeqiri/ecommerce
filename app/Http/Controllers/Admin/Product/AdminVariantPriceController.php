<?php

namespace App\Http\Controllers\Admin\Product;

use Exception;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Data\VariantPriceData;
use App\Services\VariantService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use App\Http\Requests\Variant\StoreVariantPriceRequest;
use App\Http\Requests\Variant\UpdateVariantPriceRequest;

class AdminVariantPriceController extends ApiController
{
    public function show(Variant $variant) : JsonResponse
    {
        return $this->showOne(new VariantResource($variant->load(['variant_prices'])));
    }

    public function store(StoreVariantPriceRequest $request, Variant $variant, VariantService $variantService): JsonResponse
    {
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
        try {
            $variantPriceData = VariantPriceData::fromRequest($request->validated());
            $variantService->updateVariantPrice($variant, $variantPrice, $variantPriceData);

            return $this->showOne(new VariantResource($variant->load('variant_prices')));
        } catch (Exception $e) {
            return $this->showError($e->getMessage(), 422);
        }
    }

    public function destroy(Variant $variant, VariantPrice $variantPrice): JsonResponse
    {
        abort_if($variant->id != $variantPrice->variant_id, 422, "Pricing doesn't belong to the product variant");

        $variantPrice->delete();

        return $this->showMessage('Variant Pricing deleted successfully!');
    }
}
