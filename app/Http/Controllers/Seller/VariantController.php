<?php

namespace App\Http\Controllers\Seller;

use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UploadImageService;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreVariantRequest;

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
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
