<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Variant;

class VariantService
{
    public function createVariant($data): Variant
    {
        $data['product_id'] = Product::where('uuid', $data['product_id'])->firstOrFail()->id;
        $newVariant = Variant::create($data);

        return $newVariant;
    }

    public function updateProduct(Variant $product, $data)
    {

    }

    public function deleteProduct($productId)
    {
        // Common logic for product deletion
    }
}
