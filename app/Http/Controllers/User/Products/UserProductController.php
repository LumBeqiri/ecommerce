<?php

namespace App\Http\Controllers\User\Products;

use App\Data\ProductData;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use App\values\Roles;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;

class UserProductController extends ApiController
{
    public function index(): JsonResponse
    {
        $productQuery = $this->productQueryForRole(auth()->user());

        $products = QueryBuilder::for($productQuery)
            ->allowedIncludes(['variants', 'variant_prices'])
            ->get();

        return $this->showAll(ProductResource::collection($products));
    }

    public function store(StoreProductRequest $request, ProductService $productService): JsonResponse
    {
        $this->authorize('create', Product::class);

        $productData = ProductData::from($request);
        try {
            $product = $productService->createProduct($productData);

            return $this->showOne(new ProductResource($product));
        } catch (Exception $ex) {
            return $this->respondInvalidQuery($ex->getMessage());
        }
    }

    public function show(Product $product): JsonResource
    {
        $this->authorize('view', $product);

        return new ProductResource($product->load(['variants.variant_prices']));
    }

    public function update(UpdateProductRequest $request, Product $product, ProductService $productService): JsonResource|JsonResponse
    {
        $this->authorize('update', $product);

        $productData = ProductData::from($product->toArray(), $request->validated());

        try {
            $updatedProduct = $productService->updateProduct($product, $productData);
        } catch (Exception $ex) {
            return $this->showError($ex->getMessage());
        }

        return new ProductResource($updatedProduct);

    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $product->categories()->detach();

        $product->delete();

        return $this->showMessage('Product deleted successfully');
    }

    protected function productQueryForRole(User $user): Builder
    {
        return match (true) {
            $user->hasRole(Roles::VENDOR) => Product::where('vendor_id', $user->vendor?->id),
            $user->hasRole(Roles::STAFF) => Product::where('vendor_id', $user->staff?->vendor_id),
            default => Product::query(),
        };
    }
}
