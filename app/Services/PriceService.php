<?php

namespace App\Services;

use App\Models\VariantPrice;
use Brick\Money\Money;

class PriceService
{
    public static function variantPriceToDisplay(VariantPrice $variantPrice): float
    {
        $variantPrice = VariantPrice::with('region.currency')->find($variantPrice->id);
        $priceNumericFormat = Money::ofMinor($variantPrice->price, $variantPrice->region->currency->code);

        return $priceNumericFormat->getAmount()->toFloat();
    }

    public static function priceToSave(Money $money): int
    {
        return $money->getMinorAmount()->toInt();
    }
}
