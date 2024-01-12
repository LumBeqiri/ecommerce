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

    public function addVariantAttributes(Variant $variant, array $attributeIds){
        $variant->attributes()->sync($attributeIds);
    }
}
