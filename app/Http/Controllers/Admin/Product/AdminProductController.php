<?php

namespace App\Http\Controllers\Admin\Product;

use App\Data\ProductData;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Variant;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class AdminProductController extends ApiController
{
    public function index(): JsonResponse
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedIncludes(['variants', 'variant_prices'])
            ->get();

        return $this->showAll(ProductResource::collection($products));
    }

    public function show(Product $product): JsonResponse
    {
        return $this->showOne(new ProductResource($product->load(['variants.variant_prices'])));
    }

    public function store(StoreProductRequest $request, ProductService $productService): JsonResponse
    {
        $productData = ProductData::from($request);


        $product = $productService->createProduct($productData);

        return $this->showOne(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, Product $product, ProductService $productService): JsonResponse
    {

        $this->authorize('update', $product);

        $product = $productService->updateProduct($product, $request->validated());

        return $this->showOne(new ProductResource($product));
    }

    protected function removeCategories(int $start_id, int $end_id): void
    {
        $products = Product::all();
        foreach ($products as $product) {
            if ($product->id >= $start_id && $product->id <= $end_id) {
                $product->categories()->detach();
            }
        }
    }

    public function destroy(Product $product): JsonResponse
    {

        $product->categories()->detach();
        $product->delete();

        return $this->showMessage('Product deleted successfully!');
    }
}
