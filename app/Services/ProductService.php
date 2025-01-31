<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function createProduct($data): Product
    {

        /** @var User $user */
        $user = Auth::user();

        if ($user->staff) {
            $vendor = auth()->user()->staff->vendor_id;
        }

        if ($user->hasRole('vendor')) {
            $vendor = Vendor::where('user_id', auth()->user()->id)->firstOrFail();
        }

        $categoriesulids = $data['categories'];

        $categories = Category::whereIn('ulid', $categoriesulids)->get();

        $product = Product::create(
            Arr::except($data, 'categories') + ['vendor_id' => $vendor->id]
        );

        $product->categories()->sync($categories);

        return $product;
    }

    public function createProductAndVariant(array $data): Product
    {
        $product = $this->createProduct($data);

        Variant::create([
            'variant_name' => $data['product_name'],
            'product_id' => $product->id,
        ]);

        return $product->load('variants');
    }

    public function updateProduct(Product $product, $data): Product
    {
        $updateProductData = Arr::except($data, 'categories');
        $product->fill($updateProductData);

        if (Arr::has($data, 'categories')) {
            $categories = Category::all()->whereIn('ulid', $data['categories'])->pluck('id');
            $product->categories()->sync($categories);
        }

        $product->save();

        return $product;
    }

    public function syncProductCategories(Product $product, array $categoriesUlids): Product
    {
        // If you want to re-use the logic in updateProduct, you could simply call that method,
        // or separate it out to keep updateProduct focused on updating other fields.
        $categories = Category::whereIn('ulid', $categoriesUlids)->pluck('id');
        $product->categories()->sync($categories);
        
        // Optionally, update timestamps or other related logic here
        $product->save();

        return $product;
    }

    public function deleteProductCategories(Product $product): Product
    {
        $product->categories()->detach();
        // If needed, do additional cleanup or logging
        return $product;
    }
}
