<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Vendor;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class VendorProductController extends ApiController
{
    public function index(): JsonResponse
    {
        $vendor = Vendor::where('user_id', auth()->id())->first();
        $products = QueryBuilder::for(Product::class)
            ->allowedIncludes(['variants', 'variant_prices'])
            ->where('vendor_id', $vendor->id)
            ->get();

        return $this->showAll(ProductResource::collection($products));
    }

    public function show(Product $product): JsonResponse
    {
        // disabling this check for now
        // $this->authorize('view', $product);

        return $this->showOne(new ProductResource($product->load(['variants.variant_prices'])));
    }

    public function store(StoreProductRequest $request, ProductService $productService): JsonResponse
    {

        $productData = $request->validated();

        DB::beginTransaction();
        try {
            $product = $productService->createProduct($productData);

            Variant::create([
                'variant_name' => $request->input('product_name'),
                'product_id' => $product->id,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->showMessage($e->getMessage(), $e->getCode());
        }

        return $this->showOne(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, Product $product, ProductService $productService): JsonResponse
    {

        $this->authorize('update', $product);

        $product = $productService->updateProduct($product, $request->validated());

        return $this->showOne(new ProductResource($product));
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $product->categories()->detach();
        $product->delete();

        return $this->showMessage('Product deleted successfully!');
    }
}
