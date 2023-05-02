<?php

namespace Database\Seeders;

use App\Models\DiscountCondition;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DiscountConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiscountCondition::factory(5)->create()->each(
            function ($discount_condition) {
                $products = Product::all()->random(mt_rand(1, 5))->pluck('id');
                $discount_condition->products()->attach($products);
                $discount_condition->products()->attach([1, 2, 3]);
            }
        );
    }
}
