<?php

namespace App\Services;

use App\Models\VariantPrice;
use Brick\Money\Money;

class PriceService
{
    public static function variantPriceToDisplay(VariantPrice $variantPrice): float
    {
        $variantPrice = VariantPrice::with('region.currency')->find($variantPrice->id);
        if ($variantPrice->region->currency->has_cents) {
            return $variantPrice->price / 100;
        }

        return $variantPrice->price;
    }

    public static function priceToSave(Money $money): int
    {
        return $money->getMinorAmount()->toInt();
    }
}
