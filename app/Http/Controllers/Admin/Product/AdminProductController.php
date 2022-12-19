<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Seller;
use App\Models\Product;
use App\Services\UploadImageService;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;

class AdminProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(ProductResource::collection(Product::all()));
    }

    /**
     * @param Product $product
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        return $this->showOne(new ProductResource($product));
    }


    /**
     * @param UpdateProductRequest $request
     * @param Seller $seller
     * @param Product $product
     * 
     * @return \Illuminate\Http\JsonResponse
     */
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

        $product->save();

        return $this->showOne(new ProductResource($product));
     
    }

    /**
     * Remove the specified resource from storage.
     * See ProductObserver
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->categories()->detach();
        $product->orders()->detach();
        $product->delete();
        
        return $this->showMessage('Product deleted successfully!');
    }

    /**
     * Delete category from product
     * @param Product $product
     * @param Category $category
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_product_category(Product $product, Category $category){
        $product->categories()->detach($category);
        return $this->showMessage('Category removed successfully!');
    }
}
