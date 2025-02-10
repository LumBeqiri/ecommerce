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
            $region = Region::find($variantPriceData->region_id);
            $money = Money::of($variantPriceData->price, $region->currency->code);
            $variantPrice = VariantPrice::firstOrCreate(
                [
                    'region_id' => $variantPriceData->region_id,
                    'variant_id' => $variant->id,
                ],
                [
                    'price' => $money->getMinorAmount()->toInt(),
                    'region_id' => $variantPriceData->region_id,
                    'currency_id' => $variantPriceData->currency_id,
                    'variant_id' => $variant->id,
                    'min_quantity' => $variantPriceData->min_quantity,
                    'max_quantity' => $variantPriceData->max_quantity,
                ]);

            return $variantPrice;

        } catch (Exception $ex) {
            throw $ex;
        }

    }

    public function updateVariantPrice(Variant $variant, VariantPrice $variantPrice, VariantPriceData $data): Variant
    {
        $region = Region::find($data->region_id);
        $money = Money::of($data->price, $region->currency->code);

        $variantPrice->update([
            'price' => $money->getMinorAmount()->toInt(),
            'region_id' => $region->id,
            'variant_id' => $variant->id,
            'min_quantity' => $data->min_quantity,
            'max_quantity' => $data->max_quantity,
        ]);

        return $variant->refresh();
    }
}
