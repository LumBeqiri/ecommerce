<?php

namespace Database\Factories;

use App\Models\Region;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class VariantPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'price' => $this->faker->randomNumber(1, 400),
            'variant_id' => Variant::all()->random()->id,
            'region_id' => Region::all()->random()->id,
            'min_quantity' => $this->faker->randomNumber(1, 4),
            'max_quantity' => $this->faker->randomNumber(1, 4),
        ];
    }
}
