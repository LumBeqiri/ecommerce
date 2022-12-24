<?php

use App\Services\PriceService;

it('can change euro to cents', function(){
    $price = 43.33;
    $expected = 4333;
    $cents = PriceService::priceToCents($price);

    $this->assertEquals($cents, $expected);
});


it('can change cents to euro', function(){
    $cents =4333;
    $expected = 43.33;
    $cents = PriceService::priceToEuro($cents);

    $this->assertEquals($cents, $expected);
});