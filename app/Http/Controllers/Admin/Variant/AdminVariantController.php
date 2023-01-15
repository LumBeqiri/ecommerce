<?php

namespace App\Http\Controllers\Admin\Variant;

use App\Models\Variant;
use App\Services\UploadImageService;
use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use App\Http\Requests\UpdateVariantRequest;
use App\Models\VariantPrice;

class AdminVariantController extends ApiController
{
        /**
     * Update the specified resource in storage.
     *
     * @param  UpdateVariantRequest $request
     * @param  Variant $variant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVariantRequest $request,Variant $variant){
   
        $request->validated();
        $images = $request->images;
    
        if($request->has('images')){
            $images = $request->images;
            $request_images = count($request->file('images'));

            abort_if( $request_images > 1, 422, 'Can not update more than 1 image per variant');
            
            UploadImageService::upload($variant,$images, Variant::class);
        }
        
        $variant->fill($request->except(['categories', 'attrs','price']));
        if($request->has('price')){ VariantPrice::where('variant_id', $variant->id)->update(['price' => $request->price]);}

        $attr = $request->attrs;

        $variant->attributes()->sync($attr);

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
        $variant->delete();

        return $this->showOne(new VariantResource($variant));
    }
}
