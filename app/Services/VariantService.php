<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Region;
use App\Models\Variant;
use App\Models\VariantPrice;
use Brick\Money\Money;

class VariantService
{
    public function createVariant($data): Variant
    {
        $data['product_id'] = Product::where('uuid', $data['product_id'])->firstOrFail()->id;
        $newVariant = Variant::create($data);

        return $newVariant;
    }

    public function addVariantAttributes(Variant $variant, array $attributeIds)
    {
        $variant->attributes()->sync($attributeIds);
    }

    public function addVariantPrice(Variant $variant, array $data): VariantPrice
    {
        $region = Region::where('uuid', $data['region_id'])->first();
        $money = Money::of($data['price'], $region->currency->code);

        $variantPrice = VariantPrice::firstOrCreate(
            [
                'region_id' => $region->id,
                'variant_id' => $variant->id,
            ],
            [
                'price' => $money->getMinorAmount()->toInt(),
                'region_id' => $region->id,
                'variant_id' => $variant->id,
                'min_quantity' => $data['min_quantity'],
                'max_quantity' => $data['max_quantity'],
            ]);

        return $variantPrice;
    }

    public function updateVariantPrice(Variant $variant, VariantPrice $variantPrice, array $data): Variant
    {
        $region = Region::where('uuid', $data['region_id'])->first();
        $money = Money::of($data['price'], $region->currency->code);

        $variantPrice = VariantPrice::where('id', $variantPrice->id)->where('variant_id', $variant->id)->update([
            'price' => $money->getMinorAmount()->toInt(),
            'region_id' => $region->id,
            'variant_id' => $variant->id,
            'min_quantity' => $data['min_quantity'],
            'max_quantity' => $data['max_quantity'],
        ]);

        return $variant->refresh();
    }
}
