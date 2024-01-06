<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Region;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\VariantPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Services\VariantPriceService;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VariantResource;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

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


    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $request->validated();

        $this->authorize('update', $product);

        $product->fill($request->except(['categories']));

        if ($request->has('categories')) {
            $categories = Category::all()->whereIn('uuid', $request->categories)->pluck('id');
            abort_if(count($categories) > 5, 422, 'Only 5 categories per product');
            $product->categories()->sync($categories);
        }

        $product->save();

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

    public function delete_product_category(Product $product, Category $category): JsonResponse
    {
        $product->categories()->detach($category);

        return $this->showMessage('Category removed successfully!');
    }
}
