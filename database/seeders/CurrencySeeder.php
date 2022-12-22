<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
                'name' => 'Euro',
                'code' => 'EUR',
                'symbol' => '€'
            ]
        );
    }
}
