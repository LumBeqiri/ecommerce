<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Region;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'ulid' => Str::ulid(),
            'price' => $this->faker->randomNumber(1, 400),
            'variant_id' => Variant::all()->random()->id,
            'region_id' => 1, // Region::all()->random()->id,
            'currency_id' => Currency::all()->random()->id,
            'min_quantity' => $this->faker->randomNumber(1, 4),
            'max_quantity' => $this->faker->randomNumber(1, 4),
        ];
    }
}
