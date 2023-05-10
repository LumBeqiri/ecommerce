<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VariantResource;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\Region;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Services\VariantPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminProductController extends ApiController
{
    public function index(): JsonResponse
    {
        $products = Product::all();

        return $this->showAll(ProductResource::collection($products));
    }

    public function show(Product $product): JsonResponse
    {
        return $this->showOne(new ProductResource($product));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $request->validated();
        $seller = auth()->user();

        $product_data = [
            'product_name',
            'product_short_description',
            'product_long_description',
            'seller_id',
            'status',
            'publish_status',
            'discountable',
            'origin_country',
        ];

        $variant = DB::transaction(function () use ($request, $product_data, $seller) {
            $product = Product::create($request->only($product_data) + ['seller_id' => $seller->id]);

            $categories = Category::all()->whereIn('uuid', $request->categories)->pluck('id');

            $attributes = $request->product_attributes;

            foreach ($attributes as $attribute) {
                Attribute::create([
                    'attribute_type' => $attribute['attribute_type'],
                    'attribute_value' => $attribute['attribute_value'],
                    'product_id' => $product->id,

                ]);
            }

            $product->categories()->sync($categories);

            $variant_data = $request->except(['categories', 'product_attributes', 'variant_prices', ...$product_data]);

            $variant = Variant::create($variant_data + ['product_id' => $product->id]);

            foreach ($request->variant_prices as $variant_price) {
                $region = Region::where('uuid', $variant_price['region_id'])->first();

                $price = VariantPriceService::priceToSave($variant_price['price'], $region);

                VariantPrice::create([
                    'price' => $price,
                    'variant_id' => $variant->id,
                    'region_id' => $region->id,
                    'max_quantity' => $variant_price['max_quantity'],
                    'min_quantity' => $variant_price['min_quantity'],
                ]);
            }

            return $variant;
        });

        return $this->showOne(new VariantResource($variant));
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
