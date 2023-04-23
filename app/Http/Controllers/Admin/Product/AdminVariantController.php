<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateVariantRequest;
use App\Http\Resources\VariantResource;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\Region;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Services\UploadImageService;
use App\Services\VariantPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
        $request->validated();

        $images = $request->medias;

        // if ($request->has('medias')) {
        //     $request_images = count($request->file('medias'));

        //     abort_if($request_images > 1, 422, 'Can not update more than 1 image per variant');

        //     UploadImageService::upload($variant, $images, Variant::class);
        // }

        DB::transaction(function () use ($variant, $request) {
            $variant->fill($request->except(['categories', 'attributes', 'product_id', 'variant_price', 'region']));

            if ($request->has('product_id')) {
                $product = Product::where('uuid', $request->product_id)->firstOrFail();
                $variant->product_id = $product->id;
            }

            if ($request->has('attributes')) {
                $attributes = Attribute::all()->whereIn('uuid', $request->attributes)->pluck('id');
                $variant->attributes()->sync($attributes);
            }

            if ($request->has('variant_price')) {
                $region = Region::where('uuid', $request->region)->firstOrFail();
                $variantPrice = VariantPrice::where('variant_id', $variant->id)->where('region_id', $region->id)->firstOrFail();
                $price = VariantPriceService::priceToSave($request->variant_price, $region);
                $variantPrice->price = $price;
                // dd($price);
                $variantPrice->save();
            }
            $variant->save();
        });

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

    /**
     * @param  array<string, mixed>  $variant_prices
     */
    private function updateVariantPrice(array $variant_prices, Variant $variant): void
    {
        foreach ($variant_prices as $variant_price) {
            $product_price['region_id'] = Region::where('uuid', $variant_price['region_id'])->firstOrFail()->id;
            $variant_price['variant_id'] = $variant->id;

            VariantPrice::where('variant_id', $variant->id)
            ->where('region_id', $variant_price['region_id'])
            ->update([
                'region_id' => $variant_price['region_id'],
                'price' => $variant_price['price'],
                'min_quantity' => $variant_price['min_quantity'],
                'max_quantity' => $variant_price['max_quantity'],
            ]);
        }
    }
}
