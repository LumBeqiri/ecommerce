<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\TaxProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Region>
 */
class RegionFactory extends Factory
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
            'title' => $this->faker->word(),
            'currency_id' => Currency::all()->random()->id,
            'tax_rate' => $this->faker->randomDigit(4,30),
            'tax_code' => $this->faker->randomElement(['TEST110', 'TEST2002']),
            'tax_provider_id' => TaxProvider::all()->random()->id
        ];
    }
}
