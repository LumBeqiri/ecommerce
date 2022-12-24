<?php
namespace App\Services;

class PriceService{

    public static function priceToEuro(int $priceInCents) : float
    {
        return $priceInCents/100;
    }


    public static function priceToCents($priceInEuro) : int
    {
        return $priceInEuro * 100;
    }


}