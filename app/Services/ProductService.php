<?php 
namespace App\Services;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Arr;


class ProductService{


    public function createProduct($data) : Product
    {
        $vendor = Vendor::where('user_id', auth()->user()->id)->firstOrFail();

        $categoriesUuids = $data['categories'];

        $categories = Category::whereIn('uuid', $categoriesUuids)->get();

        $product = Product::create(
            Arr::except($data,'categories') + ['vendor_id' => $vendor->id]
        );
        
        $product->categories()->sync($categories);

        return $product;
    }

    public function updateProduct($productId, $data)
    {
        // Common logic for product update
    }

    public function deleteProduct($productId)
    {
        // Common logic for product deletion
    }
}