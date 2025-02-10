<?php

namespace App\Services;

use App\Data\VariantData;
use App\Data\VariantPriceData;
use App\Models\Region;
use App\Models\Variant;
use App\Models\VariantPrice;
use Brick\Money\Money;
use Exception;

class VariantService
{
    public function createVariant(VariantData $variantData): Variant
    {
        $newVariant = Variant::create($variantData->toArray());

        return $newVariant;
    }

    /**
     * @param  int[]  $attributeIds
     */
    public function addVariantAttributes(Variant $variant, array $attributeIds): void
    {
        $variant->attributes()->sync($attributeIds);
    }

    public function addVariantPrice(Variant $variant, VariantPriceData $variantPriceData): VariantPrice
    {
        try {
            $region = Region::findOrFail($variantPriceData->region_id);
            $currency = $region->currency()->firstOrFail();
    
            $money = Money::of($variantPriceData->price, $currency->code);
            
            $variantPrice = VariantPrice::firstOrCreate(
                [
                    'region_id' => $variantPriceData->region_id,
                    'variant_id' => $variant->id,
                ],
                [
                    'price' => $money->getMinorAmount()->toInt(),
                    'region_id' => $variantPriceData->region_id,
                    'currency_id' => $currency->id,
                    'variant_id' => $variant->id,
                    'min_quantity' => $variantPriceData->min_quantity,
                    'max_quantity' => $variantPriceData->max_quantity,
                ]
            );
    
            return $variantPrice;
        } catch (Exception $ex) {
            // Log the error or handle it as needed
            throw $ex;
        }
    }

    public function updateVariantPrice(Variant $variant, VariantPrice $variantPrice, VariantPriceData $data): Variant
    {
        $region = Region::findOrFail($data->region_id);
        $money = Money::of($data->price, $region->currency->code);

        $variantPrice->update([
            'price' => $money->getMinorAmount()->toInt(),
            'region_id' => $data->region_id,
            'variant_id' => $variant->id,
            'min_quantity' => $data->min_quantity,
            'max_quantity' => $data->max_quantity,
        ]);

        return $variant->refresh();
    }
}
