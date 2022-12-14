<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use App\Models\Product;

use App\Services\UploadImageService;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VariantResource;
use App\Models\User;
use App\Models\Variant;
use Exception;

class SellerProductController extends ApiController
{
    public function index(Seller $seller){
        $products = $seller->products;

        return $this->showAll(ProductResource::collection($products));
    }


    public function store(StoreProductRequest $request, User $seller){

        //validate product details and image details
        $variant_data = $request->validated();
        $product_data = [];

        $images = $request->file('medias');

        $product_data = $request->only(['name','seller_id','currency_id']);
        $product_data['description'] = $request->short_description;
        $product_data['seller_id'] = $seller->id;

        $newProduct = Product::create($product_data);
        
        //getting categories from request
        //cast string to array of integer
        // $integerIDs = array_map('intval', explode(',', $request->categories));
        $categories = $request->categories;
        
        abort_if(in_array(0,$categories),422, 'Category cannot be 0');
        abort_if(count($categories) >5, 422, 'Only 5 categories per product');

        $newProduct->categories()->sync($categories);


        $variant_data['product_id'] = $newProduct->id;

        $newVariant = Variant::create([
            'product_id' => $newProduct->id,
            'sku' => $variant_data['sku'],
            'variant_name' => $variant_data['variant_name'],
            'short_description' => $variant_data['short_description'],
            'long_description' => $variant_data['long_description'],
            'price' => $variant_data['price'],
            'stock' => $variant_data['stock'],
            'status' => $variant_data['status'],
        ]);

        try{
            //send images to be uploaded
            UploadImageService::upload($newVariant,$images, Variant::class);
        }catch(Exception $e){
            Variant::destroy($newVariant->id);
            Product::destroy($newProduct->id);
            $newVariant = null;
            return $this->errorResponse("File(s) could not be uploaded", 500);
        }

       
        return $this->showOne(new VariantResource($newVariant),201);
    }

    public function update(UpdateProductRequest $request, Seller $seller, Product $product){
        $request->validated();
        $images = null;

        if($request->has('medias')){
            $images = $request->file('medias');
            $request_images = count($request->file('medias'));

            abort_if( $request_images > 1, 422, 'Can not have more than 1 image per thumbnail');

            UploadImageService::upload($product,$images, Product::class);
        }

        $product->fill($request->except(['categories']));   

        //get ids integer
        $categories =  $request->categories;
        //no more than 5 images are allowed per product
        if($request->has('categories')){
            abort_if(count($categories) >5, 422, 'Only 5 categories per product');

            $product->categories()->sync($categories);
        }

        $product->save();

        return $this->showOne(new ProductResource($product));
     
    }



    protected function checkSeller(Seller $seller, Product $product){
        abort_if($seller->id != $product->seller_id,
        422,
        'The specified seller is not the seller of this product!');
    }


    protected function removeCategories($start_id,$end_id){
        $products = Product::all();
        foreach($products as $product){
            if($product->id >=$start_id && $product->id <=$end_id){
                $product->categories()->detach();
            }
        }
    }
}
