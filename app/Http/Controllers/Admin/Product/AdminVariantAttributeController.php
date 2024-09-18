<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Variant\VariantAttributeRequest;
use App\Http\Resources\VariantResource;
use App\Models\Attribute;
use App\Models\Variant;
use App\Services\VariantService;
use Illuminate\Http\JsonResponse;

class AdminVariantAttributeController extends ApiController
{
    public function update(VariantAttributeRequest $request, Variant $variant, VariantService $variantService): JsonResponse
    {

        $attributeIds = Attribute::whereIn('ulid', $request->input('attributes'))->pluck('id')->toArray();

        $variantService->addVariantAttributes($variant, $attributeIds);

        $variant->refresh();

        return $this->showOne(new VariantResource($variant->load('variant_prices')));
    }
}
