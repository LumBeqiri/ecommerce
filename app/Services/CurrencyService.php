<?php
namespace App\Services;

use App\Models\Currency;

class CurrencyService{
    
    public static function create(){
        Currency::create(
            [
                'name' => 'Leke',
                'code' => 'ALL',
                'symbol' => 'Lek'
            ]
        );
        Currency::create(
            [
                'name' => 'Dollars',
                'code' => 'USD',
                'symbol' => '$'
            ]
        );
        Currency::create(
            [
                'name' => 'Afghanis',
                'code' => 'AFN',
                'symbol' => '؋'
            ]
        );
        Currency::create(
            [
                'name' => 'Pesos',
                'code' => 'ARS',
                'symbol' => '$'
            ]
        );
        Currency::create(
            [
                'name' => 'Guilders',
                'code' => 'AWG',
                'symbol' => 'ƒ'
            ]
        );
        Currency::create(
            [
                'name' => 'Dollars',
                'code' => 'AUD',
                'symbol' => '$'
            ]
        );
        Currency::create(
            [
                'name' => 'New Manats',
                'code' => 'AZN',
                'symbol' => 'ман'
            ]
        );
        Currency::create(
            [
                'name' => 'Dollars',
                'code' => 'BSD',
                'symbol' => '$'
            ]
        );
        Currency::create(
            [
                'name' => 'Dollars',
                'code' => 'BBD',
                'symbol' => '$'
            ]
        );
        Currency::create(
            [
                'name' => 'Rubles',
                'code' => 'BYR',
                'symbol' => 'p.'
            ]
        );
        Currency::create(
            [
                'name' => 'Euro',
                'code' => 'EUR',
                'symbol' => '€'
            ]
        );
    }
}