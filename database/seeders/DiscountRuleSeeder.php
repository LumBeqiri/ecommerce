<?php

namespace Database\Seeders;

use App\Models\DiscountRule;
use App\values\DiscountAllocationTypes;
use App\values\DiscountRuleTypes;
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
        DiscountRule::factory()->create([
            'discount_type' => DiscountRuleTypes::PERCENTAGE,
            'value' => 10,
            'allocation' => DiscountAllocationTypes::ITEM_SPICIFIC
        ]);
        DiscountRule::factory()->count(9)->create();
    }
}
