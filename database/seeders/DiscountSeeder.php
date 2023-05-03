<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Discount::factory()->create([
            'discount_rule_id' => 1,
            'code' => 'lum',
        ]);

        Discount::factory(5)->create();
    }
}
