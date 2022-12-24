<?php

namespace App\Http\Controllers\Seller;


use App\Models\Product;
use App\Models\Variant;
use App\Services\UploadImageService;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateVariantRequest;
use App\Http\Resources\VariantResource;
use App\Models\Attribute;
use App\Services\PriceService;

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
     * @param  StoreVariantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVariantRequest $request, Product $product){

        $request->validated();
        $variant_data = $request->except('attrs','medias');

        $images = $request->file('medias');

        $variant_data['product_id'] = $product->id;
        $variant_data['price'] = PriceService::priceToCents( $variant_data['price']);

        $newVariant = Variant::create($variant_data);

        if($request->has('attrs')){
            $attrs = Attribute::all()->whereIn('uuid', $request->attrs)->pluck('id');
            $newVariant->attributes()->sync($attrs);
        }

        UploadImageService::upload($newVariant,$images, Variant::class);

        return $this->showOne(new VariantResource($newVariant));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateVariantRequest $request
     * @param  Variant $variant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVariantRequest $request,Variant $variant)
    {
        $this->authorize('update', $variant);
        $request->validated();
        $images = $request->medias;
        if($request->has('medias')){
            $images = $request->medias;
            $request_images = count($request->file('medias'));
            abort_if( $request_images > 1, 422, 'Can not update more than 1 image per variant');
            UploadImageService::upload($variant,$images, Variant::class);
        }
        
        $variant->fill($request->except(['categories', 'attrs', 'medias', 'product_id']));   

        if($request->has('product_id')){
            $product = Product::where('uuid', $request->product_id)->firstOrFail();
            $variant->product_id = $product->id;
        }

        if($request->has('attrs')){
            $attrs = Attribute::all()->whereIn('uuid', $request->attrs)->pluck('id');
            $variant->attributes()->sync($attrs);
        }


        $variant->save();

        return $this->showOne(new VariantResource($variant));
     
    }

    /**
     * @param Variant $variant
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variant $variant)
    {   
        $this->authorize('delete', $variant);

        $variant->delete();

        return $this->showOne(new VariantResource($variant));
    }
}
