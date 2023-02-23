<?php

namespace Database\Seeders;

use App\Models\Variant;
use App\Models\Attribute;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $variantsQuantity = 30;
        Variant::factory($variantsQuantity)->count($variantsQuantity)->create()->each(
            function ($variant) {
                $attributes = Attribute::all()->random()->pluck('id');
                $variant->attributes()->attach($attributes);
            }
        );
    }
}
