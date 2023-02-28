<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\UploadImageService;
use Illuminate\Http\JsonResponse;

class AdminProductController extends ApiController
{
    public function index(): JsonResponse
    {
        return $this->showAll(ProductResource::collection(Product::all()));
    }

    public function show(Product $product): JsonResponse
    {
        return $this->showOne(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, Product $product, UploadImageService $uploadService): JsonResponse
    {
        $request->validated();
        $images = null;
        if ($request->has('medias')) {
            $images = $request->file('medias');
            $request_images = count($request->file('medias'));
            abort_if($request_images > 1, 422, 'Can not have more than 1 image per thumbnail');

            $uploadService->upload($product, $images, Product::class);
        }

        $product->fill($request->except(['categories', 'seller_id']));
        if ($request->has('seller_id')) {
            $seller = User::where('uuid', $request->seller_id)->first();
            $product->seller_id = $seller->id;
        }
        $categories = Category::all()->whereIn('uuid', $request->categories)->pluck('id');

        $product->categories()->sync($categories);

        $product->save();

        return $this->showOne(new ProductResource($product));
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->categories()->detach();
        $product->orders()->detach();
        $product->delete();

        return $this->showMessage('Product deleted successfully!');
    }

    public function delete_product_category(Product $product, Category $category): JsonResponse
    {
        $product->categories()->detach($category);

        return $this->showMessage('Category removed successfully!');
    }
}
