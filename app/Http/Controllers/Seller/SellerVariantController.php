<?php

namespace App\Http\Controllers\Seller;

use App\Models\Region;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Attribute;
use App\Models\VariantPrice;
use Illuminate\Support\Facades\DB;
use App\Services\UploadImageService;
use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateVariantRequest;

class SellerVariantController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $variants = $product->variants()->get();

        return $this->showAll(VariantResource::collection($variants));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreVariantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVariantRequest $request, Product $product)
    {
        $request->validated();

        $variant_data = $request->except('attrs', 'medias', 'variant_prices');

        $variant_data['product_id'] = $product->id;

        $newVariant = DB::transaction(function () use ($variant_data, $request) {
            $newVariant = Variant::create($variant_data);

            $this->createVariantPrice($request->variant_prices, $newVariant);

            return $newVariant;
        });

        if ($request->has('attrs')) {
            $attrs = Attribute::all()->whereIn('uuid', $request->attrs)->pluck('id');
            $newVariant->attributes()->sync($attrs);
        }

        return $this->showOne(new VariantResource($newVariant));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateVariantRequest  $request
     * @param  Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVariantRequest $request, Variant $variant)
    {
        $this->authorize('update', $variant);
        $request->validated();

        $images = $request->medias;

        if ($request->has('medias')) {
            $request_images = count($request->file('medias'));

            abort_if($request_images > 1, 422, 'Can not update more than 1 image per variant');

            UploadImageService::upload($variant, $images, Variant::class);
        }

        DB::transaction(function () use ($variant, $request) {
            $variant->fill($request->except(['categories', 'attrs', 'medias', 'product_id', 'product_prices']));

            if ($request->has('product_id')) {
                $product = Product::where('uuid', $request->product_id)->firstOrFail();
                $variant->product_id = $product->id;
            }

            if ($request->has('attrs')) {
                $attrs = Attribute::all()->whereIn('uuid', $request->attrs)->pluck('id');
                $variant->attributes()->sync($attrs);
            }

            if ($request->has('variant_prices')) {
                $this->updateVariantPrice($request->variant_prices, $variant);
            }

            $variant->save();
        });

        return $this->showOne(new VariantResource($variant->load('variant_prices')));
    }

    /**
     * @param  Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variant $variant)
    {
        $this->authorize('delete', $variant);

        $variant->delete();

        return $this->showOne(new VariantResource($variant));
    }

    private function createVariantPrice($variant_prices, $newVariant)
    {
        foreach ($variant_prices as $variant_price) {
            $variant_price['region_id'] = Region::where('uuid', $variant_price['region_id'])->firstOrFail()->id;
            $variant_price['variant_id'] = $newVariant->id;
            VariantPrice::create($variant_price);
        }
    }

    private function updateVariantPrice($variant_prices, $variant)
    {
        foreach ($variant_prices as $variant_price) {
            $product_price['region_id'] = Region::where('uuid', $variant_price['region_id'])->firstOrFail()->id;
            $variant_price['variant_id'] = $variant->id;

            VariantPrice::where('variant_id', $variant->id)
            ->where('region_id', $variant_price['region_id'])
            ->update([
                'region_id' => $variant_price['region_id'],
                'currency_id' => $variant_price['currency_id'],
                'price' => $variant_price['price'],
                'min_quantity' => $variant_price['min_quantity'],
                'max_quantity' => $variant_price['max_quantity'],
            ]);
        }
    }
}
