<?php

namespace App\Http\Controllers\Seller;

use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use App\Services\UploadImageService;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateVariantRequest;
use App\Http\Resources\VariantResource;
use App\Models\Attribute;

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
        //validate variant details and image details
        $request->validated();
        $variant_data = $request->except('attrs');

        $images = $request->file('images');

        $variant_data['product_id'] = $product->id;

        $newVariant = Variant::create($variant_data);

        // $attr = array_map('intval', explode(',', $request->attrs));


        $attrs = Attribute::all()->whereIn('uuid', $request->attrs)->pluck('id');
        $newVariant->attributes()->sync($attrs);

        //send images to be uploaded
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
    public function update(UpdateVariantRequest $request,Variant $variant){
   
        $request->validated();
        $seller_id = $request->seller_id;
        $images = $request->images;
    
        if($request->has('images')){
            $images = $request->images;
            $request_images = count($request->file('images'));
            abort_if( $request_images > 1, 422, 'Can not update more than 1 image per variant');
            UploadImageService::upload($variant,$images, Variant::class);
        }
        
        
        $variant->fill($request->except(['categories']));   
        //get string ids and convert them to integer
        $this->checkSeller($seller_id,$variant->product_id);

        $attr = $request->attrs;

        $variant->attributes()->sync($attr);

        $variant->save();

        return $this->showOne(new VariantResource($variant));
     
    }

    /**
     * @param Product $product
     * @param Variant $variant
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Variant $variant)
    {   
        $variant->delete();

        return $this->showOne(new VariantResource($variant));
    }

    protected function checkSeller($seller_id, $product_id){
        $product = Product::findOrFail($product_id);
        $seller = User::findOrFail($seller_id);
        abort_if($seller->id != $product->seller_id,
        422,
        'The specified seller is not the seller of this product!');
    }
}
