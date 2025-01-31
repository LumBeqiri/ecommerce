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

        Variant::create([
            'variant_name' => $data['product_name'],
            'product_id' => $product->id,
        ]);

        $product->categories()->sync($categories);

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
     
        $categories = Category::whereIn('ulid', $categoriesUlids)->pluck('id');
        $product->categories()->sync($categories);
        
      
        $product->save();

        return $product;
    }

    public function deleteProductCategories(Product $product): Product
    {
        $product->categories()->detach();

        return $product;
    }
}
