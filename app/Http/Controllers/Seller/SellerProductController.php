<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use App\Models\Product;

use Illuminate\Foundation\Auth\User;
use App\Services\UploadImageService;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Variant;

class SellerProductController extends ApiController
{
    public function index(Seller $seller){
        $products = $seller->products;

        return $this->showAll($products);
    }

    public function update(UpdateProductRequest $request, Seller $seller, Product $product){
        $request->validated();
        $images = null;
        if($request->has('images')){
            $images = $request->images;
            $request_images = count($request->file('images'));
            abort_if( $request_images > 1, 422, 'Can not have more than 1 image per thumbnail');
            UploadImageService::upload($product,$images, Product::class);
        }
        
        $product->fill($request->except(['categories']));   
        $this->checkSeller($seller,$product);
        //get string ids and convert them to integer
        $integerIDs =  $request->categories;
        //no more than 5 images are allowed per product
        if($request->has('categories')){
            abort_if(count($integerIDs) >5, 422, 'Only 5 categories per product');
            //update the categories with the requested id's
            $product->categories()->sync($integerIDs);
        }


        $product->save();

        return $this->showOne($product);
     
    }

    public function store(StoreProductRequest $request, User $seller){
        //validate product details and image details
        $variant_data = $request->all();
        $product_data = [];

        $images = $request->file('images');

        $product_data = $request->only(['name','seller_id','currency_id']);
        $product_data['description'] = $request->short_description;
        $product_data['seller_id'] = $seller->id;

        $newProduct = Product::create($product_data);

        //getting categories from request
        //cast string to array of integer
        // $integerIDs = array_map('intval', explode(',', $request->categories));
        $integerIDs = $request->categories;
        
        abort_if(in_array(0,$integerIDs),422, 'Category cannot be 0');
        abort_if(count($integerIDs) >5, 422, 'Only 5 categories per product');

        $newProduct->categories()->sync($integerIDs);


        $variant_data['product_id'] = $newProduct->id;

        
        $newVariant = Variant::create($variant_data);

        //send images to be uploaded
        return UploadImageService::upload($newVariant,$images, Variant::class);
    }

    protected function checkSeller(Seller $seller, Product $product){
        abort_if($seller->id != $product->seller_id,
        422,
        'The specified seller is not the seller of this product!');
    }

    protected function getVariantProductData($arr){

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
