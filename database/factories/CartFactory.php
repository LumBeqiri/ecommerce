<?php

namespace Database\Factories;

use App\Models\Buyer;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ulid' => Str::ulid(),
            'buyer_id' => Buyer::all()->random()->id,
            'total_cart_price' => $this->faker->numberBetween(3, 40),
            'region_id' => Region::all()->random()->id,
        ];
    }
}
