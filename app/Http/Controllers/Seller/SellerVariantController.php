<?php

namespace App\Http\Controllers\Seller;


use App\Models\Region;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Attribute;
use App\Models\ProductPrices;
use App\Services\PriceService;
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
     * @param  StoreVariantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVariantRequest $request, Product $product){

        $request->validated();

        $variant_data = $request->except('attrs','medias', 'product_prices');

        $images = $request->file('medias');

        $variant_data['product_id'] = $product->id;

        $newVariant = DB::transaction(function () use($variant_data, $request) {
            $newVariant = Variant::create($variant_data);

            $this->createProductPrice($request->product_prices, $newVariant);

            return $newVariant;
        });

        UploadImageService::upload($newVariant,$images, Variant::class);

        if($request->has('attrs')){
            $attrs = Attribute::all()->whereIn('uuid', $request->attrs)->pluck('id');
            $newVariant->attributes()->sync($attrs);
        }


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
            $request_images = count($request->file('medias'));

            abort_if( $request_images > 1, 422, 'Can not update more than 1 image per variant');

            UploadImageService::upload($variant,$images, Variant::class);
        }

        DB::transaction(function () use ($variant, $request){
            $variant->fill($request->except(['categories', 'attrs', 'medias', 'product_id','product_prices']));   

            if($request->has('product_id')){
                $product = Product::where('uuid', $request->product_id)->firstOrFail();
                $variant->product_id = $product->id;
            }
    
            if($request->has('attrs')){
                $attrs = Attribute::all()->whereIn('uuid', $request->attrs)->pluck('id');
                $variant->attributes()->sync($attrs);
            }
    
            if($request->has('product_prices')){
                $this->updateProductPrice($request->product_prices, $variant);
            }
    
            $variant->save();
        });
        

        return $this->showOne(new VariantResource($variant->load('product_prices')));
     
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


    private function createProductPrice($product_prices, $newVariant){
        foreach($product_prices as $product_price){
            $product_price['region_id'] = Region::where('uuid',$product_price['region_id'])->firstOrFail()->id;
            $product_price['variant_id'] = $newVariant->id;
            ProductPrices::create($product_price);
        }
    }

    private function updateProductPrice($product_prices, $variant){

        foreach($product_prices as $product_price){
            $product_price['region_id'] = Region::where('uuid',$product_price['region_id'])->firstOrFail()->id;
            $product_price['variant_id'] = $variant->id;
            
            ProductPrices::where('variant_id', $variant->id)
            ->where('region_id', $product_price['region_id'])
            ->update([
                'region_id' => $product_price['region_id'],
                'currency_id' => $product_price['currency_id'],
                'price' => $product_price['price'],
                'min_quantity' => $product_price['min_quantity'],
                'max_quantity' => $product_price['max_quantity'],
            ]);
        }
    }

}
