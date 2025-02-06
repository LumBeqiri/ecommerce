<?php

namespace App\Services;

use App\Data\VariantData;
use App\Models\Currency;
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

    public function addVariantAttributes(Variant $variant, array $attributeIds)
    {
        $variant->attributes()->sync($attributeIds);
    }

    public function addVariantPrice(Variant $variant, array $data): VariantPrice
    {

        try {
            $region = Region::where('ulid', $data['region_id'])->first();
            $money = Money::of($data['price'], $region->currency->code);
            $currency = Currency::find($data['currency_id']);
            $variantPrice = VariantPrice::firstOrCreate(
                [
                    'region_id' => $region->id,
                    'variant_id' => $variant->id,
                ],
                [
                    'price' => $money->getMinorAmount()->toInt(),
                    'region_id' => $region->id,
                    'currency_id' => $currency->id,
                    'variant_id' => $variant->id,
                    'min_quantity' => $data['min_quantity'],
                    'max_quantity' => $data['max_quantity'],
                ]);

            return $variantPrice;

        } catch (Exception $ex) {
            throw $ex;
        }

    }

    public function updateVariantPrice(Variant $variant, VariantPrice $variantPrice, array $data): Variant
    {
        $region = Region::where('ulid', $data['region_id'])->first();
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
