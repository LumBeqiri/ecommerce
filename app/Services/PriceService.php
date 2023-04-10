<?php

namespace App\Services;

use App\Models\VariantPrice;

class PriceService
{
    public static function priceToDisplay(VariantPrice $variantPrice): float
    {
        if ($variantPrice->currency->has_cents) {
            return $variantPrice->price / 100;
        }

        return $variantPrice->price;
    }

    public static function priceToSave(float $priceInEuro): int
    {
        return $priceInEuro * 100;
    }
}
