<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VariantResource;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\Region;
use App\Models\Seller;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
use Illuminate\Support\Facades\DB;

class SellerProductController extends ApiController
{
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll(ProductResource::collection($products));
    }

    public function store(StoreProductRequest $request, User $seller)
    {
        $request->validated();

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
                VariantPrice::create([
                    'price' => $variant_price['price'],
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

    public function update(UpdateProductRequest $request, Seller $seller, Product $product)
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

    protected function removeCategories($start_id, $end_id)
    {
        $products = Product::all();
        foreach ($products as $product) {
            if ($product->id >= $start_id && $product->id <= $end_id) {
                $product->categories()->detach();
            }
        }
    }
}
