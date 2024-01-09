<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Region;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Attribute;
use Illuminate\Support\Arr;
use App\Models\VariantPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Services\UploadImageService;
use App\Services\VariantPriceService;
use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use App\Http\Requests\Variant\StoreVariantRequest;
use App\Http\Requests\Variant\UpdateVariantRequest;

class AdminVariantController extends ApiController
{
    public function index(Product $product): JsonResponse
    {
        $variants = $product->variants()->get();

        return $this->showAll(VariantResource::collection($variants));
    }

    public function store(StoreVariantRequest $request, Product $product): JsonResponse
    {
        $request->validated();

        $variant_data = $request->except('attributes', 'variant_prices');

        $variant_data['product_id'] = $product->id;

        $newVariant = DB::transaction(function () use ($variant_data, $request) {
            $newVariant = Variant::create($variant_data);

            $this->createVariantPrice($request->variant_prices, $newVariant);

            return $newVariant;
        });

        if ($request->has('attributes')) {
            $attributes = Attribute::all()->whereIn('uuid', $request->attributes)->pluck('id');
            $newVariant->attributes()->sync($attributes);
        }

        return $this->showOne(new VariantResource($newVariant));
    }

    public function update(UpdateVariantRequest $request, Variant $variant): JsonResponse
    {
        $this->authorize('update', $variant);

        $updateVariantData = $request->validated();
        
        $variant->fill(Arr::except($updateVariantData,'product_id'));
        $variant->save();

        return $this->showOne(new VariantResource($variant->load('variant_prices')));
    }

    public function destroy(Variant $variant): JsonResponse
    {
        $this->authorize('delete', $variant);

        $variant->delete();

        return $this->showOne(new VariantResource($variant));
    }

    /**
     * @param  array<string, mixed>  $variant_prices
     */
    private function createVariantPrice(array $variant_prices, Variant $newVariant): void
    {
        foreach ($variant_prices as $variant_price) {
            $variant_price['region_id'] = Region::where('uuid', $variant_price['region_id'])->firstOrFail()->id;
            $variant_price['variant_id'] = $newVariant->id;
            VariantPrice::create($variant_price);
        }
    }
}
