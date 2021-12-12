<?php

namespace App\Http\Controllers\Seller;

use App\Services\UploadProductService;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Foundation\Auth\User;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreProductRequest;

class SellerProductController extends ApiController
{
    public function index(Seller $seller){
        $products = $seller->products;

        return $this->showAll($products);
    }

    public function update(Seller $seller, Product $product){
        
        $images = $product->images;
        echo($images->count());
    }

    public function store(StoreProductRequest $request, User $seller){
        //validate product details and image details
        $data = $request->validated();
        $data['status'] = $request->status;
        $data['currency_id'] =$request->currency_id;
        $data['seller_id'] = $seller->id;
        $images = $request->file('images');
        // send product details and images to be uploaded
        return UploadProductService::upload($data,$images);

    }
}
