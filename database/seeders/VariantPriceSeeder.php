<?php

namespace Database\Seeders;

use App\Models\Variant;
use App\Models\VariantPrice;
use Illuminate\Database\Seeder;

class VariantPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VariantPrice::factory(Variant::count())->create();
    }
}
