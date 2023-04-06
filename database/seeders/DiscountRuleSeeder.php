<?php

namespace Database\Seeders;

use App\Models\DiscountRule;
use Illuminate\Database\Seeder;

class DiscountRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiscountRule::factory()->count(5)->create();
    }
}