<?php

namespace App\Http\Controllers\Seller;

use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UploadImageService;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateVariantRequest;
use GuzzleHttp\Handler\Proxy;

class VariantController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $variants = $product->variants()->get();
        
        return $this->showAll($variants);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVariantRequest $request, Product $product){
        //validate variant details and image details
        $variant_data = $request->all();

        $images = $request->file('images');


        $variant_data['product_id'] = $product->id;

        
        $newVariant = Variant::create($variant_data);

        //send images to be uploaded
        return UploadImageService::upload($newVariant,$images, Variant::class);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Variant $variant)
    {
        return $this->showOne($variant);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVariantRequest $request,Product $product,Variant $variant){
   
        $request->validated();
        $seller_id = $request->seller_id;
        $images = null;
        $images = $request->images;
    
        if($request->has('images')){
            $images = $request->images;
            $request_images = count($request->file('images'));
            abort_if( $request_images > 1, 422, 'Can not have more than 1 image per variant');
            UploadImageService::upload($variant,$images, Variant::class);
        }
        
        
        $variant->fill($request->except(['categories']));   
        //get string ids and convert them to integer
        $this->checkSeller($seller_id,$variant->product_id);

        $variant->save();

        return $this->showOne($variant);
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function checkSeller($seller_id, $product_id){

        $product = Product::findOrFail($product_id);
        $seller = Seller::findOrFail($seller_id);
        abort_if($seller->id != $product->seller_id,
        422,
        'The specified seller is not the seller of this product!');
    }
}
