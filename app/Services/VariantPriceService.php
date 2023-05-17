<?php

namespace App\Services;

use App\Models\Region;
use App\Models\VariantPrice;

class VariantPriceService
{
    public static function variantPriceToDisplay(VariantPrice $variantPrice): float
    {
        $variantPrice = VariantPrice::with('region.currency')->find($variantPrice->id);
        if ($variantPrice->region->currency->has_cents) {
            return $variantPrice->price / 100;
        }

        return $variantPrice->price;
    }

    public static function priceToSave(int|float $price, Region $region): int
    {
        if ($region->currency->has_cents) {
            $price *= 100;

            return (int) $price;
        }

        return (int) $price;
    }
}
