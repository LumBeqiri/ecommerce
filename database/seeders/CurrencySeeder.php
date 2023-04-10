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
                'id' => 1,
                'name' => 'Leke',
                'code' => 'ALL',
                'symbol' => 'Lek',
                'has_cents' => false,
            ]
        );
        Currency::create(
            [
                'id' => 2,
                'name' => 'Dollars',
                'code' => 'USD',
                'symbol' => '$',
                'has_cents' => true,
            ]
        );

        Currency::create(
            [
                'id' => 3,
                'name' => 'Euro',
                'code' => 'EUR',
                'symbol' => '€',
                'has_cents' => true,
            ]
        );
    }
}
