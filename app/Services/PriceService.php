<?php

namespace App\Services;

class PriceService
{
    //hello

    public static function priceToEuro(int $priceInCents): float
    {
        return $priceInCents / 100;
    }

    public static function priceToCents(int $priceInEuro): int
    {
        return $priceInEuro * 100;
    }
}
