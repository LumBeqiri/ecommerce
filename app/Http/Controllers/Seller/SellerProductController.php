<?php

namespace App\Http\Controllers\Seller;

use App\Models\Image;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreProductRequest;

class SellerProductController extends ApiController
{
    public function index(Seller $seller){
        $products = $seller->products;

        return $this->showAll($products);
    }

    public function store(StoreProductRequest $request, User $seller){

        $data = $request->validated();

        $data['status'] = Product::AVAILABLE_PRODUCT;
        $data['currency_id'] =$request->currency_id;
        $data['seller_id'] = $seller->id;
        
        return DB::transaction(function() use ($request, $data){
            $newProduct = Product::create($data);
            $imgData['image'] = $request->image->store('img');
            $imgData['product_id'] = $newProduct->id;
            Image::create($imgData);

            return $this->showOne($newProduct);
        });
    }
}
