<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use App\Models\Product;

use Illuminate\Foundation\Auth\User;
use App\Services\UploadProductService;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;

class SellerProductController extends ApiController
{
    public function index(Seller $seller){
        $products = $seller->products;

        return $this->showAll($products);
    }

    public function update(StoreProductRequest $request, Seller $seller, Product $product){
        $request->validated();
        $images = null;
        if($request->has('images')){
            $images = $request->images;
            $request_images = count($request->file('images'));
            $db_images_count = $product->images->count();
            abort_if($db_images_count + $request_images > 5, 422, 'Can not have more than 5 images per product');
            UploadProductService::upload($product,$images);
        }
        $product->fill($request->except(['categories']));
        $product->price = $request->price *100;
        $this->checkSeller($seller,$product);
        //get string ids and convert them to integer
        $integerIDs = array_map('intval', explode(',', $request->categories));
        //no more than 5 images are allowed per product
        abort_if(count($integerIDs) >5, 422, 'Only 5 categories per product');
        //update the categories with the requested id's
        $product->categories()->syncWithoutDetaching($integerIDs);

        $product->save();

        return $this->showOne($product);
     
    }

    public function store(StoreProductRequest $request, User $seller){
        //validate product details and image details
        $data = $request->validated();
        $data['price'] = $request->price * 100;
        $data['currency_id'] =$request->currency_id;
        $data['seller_id'] = $seller->id;
        $images = $request->file('images');
        $newProduct = Product::create($data);
        //cast string to array of integer
        $integerIDs = array_map('intval', explode(',', $request->categories));
        foreach($integerIDs as $id){
            if($id <=0){
                abort(422, 'Category cannot be 0');
            }
        }
        abort_if(count($integerIDs) >5, 422, 'Only 5 categories per product');
        $newProduct->categories()->sync($integerIDs);
        //send images to be uploaded
        return UploadProductService::upload($newProduct,$images);


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
