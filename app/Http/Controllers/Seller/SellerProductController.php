<?php

namespace App\Http\Controllers\Seller;

use Exception;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Services\UploadImageService;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VariantResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class SellerProductController extends ApiController
{
    public function index(Seller $seller){
        $products = $seller->products;

        return $this->showAll(ProductResource::collection($products));
    }

    public function store(StoreProductRequest $request, User $seller){
        $request->validated();

        $product_data = [
            'name',
            'product_short_description',
            'product_long_description',
            'seller_id',
            'status',
            'publish_status',
            'discountable',
            'origin_country', 
        ];


        $variant = DB::transaction(function () use ($request, $product_data, $seller){
            $product = Product::create($request->only($product_data) + ['seller_id' => $seller->id]);

            $categories = Category::all()->whereIn('uuid', $request->categories)->pluck('id');
    
            $product->categories()->sync($categories);
          
            $variant_data = $request->except(['categories','variant_prices',...$product_data]);
    
            $variant = Variant::create($variant_data + ['product_id' => $product->id]);

            return $variant;
        });
       
        return $this->showOne(new VariantResource($variant));

    }

    public function update(UpdateProductRequest $request, Seller $seller, Product $product){
        $request->validated();

        $this->authorize('update', $product);

        // $images = null;

        // if($request->has('medias')){
        //     $images = $request->file('medias');
        //     $request_images = count($request->file('medias'));

        //     abort_if( $request_images > 1, 422, 'Can not have more than 1 image per thumbnail');

        //     UploadImageService::upload($product,$images, Product::class);
        // }
    
        $product->fill($request->except(['categories']));

        if($request->has('categories')){
            $categories = Category::all()->whereIn('uuid', $request->categories)->pluck('id');
            abort_if(count($categories) >5, 422, 'Only 5 categories per product');
            $product->categories()->sync($categories);
        }

        $product->save();
        
        return $this->showOne(new ProductResource($product));
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
