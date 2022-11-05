<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
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
            'name' => $this->faker->word,
            'desc' => $this->faker->paragraph(1),
            'discount_percent' => $this->faker->numberBetween(1,90),
            'active' => $this->faker->boolean(60),
            'valid_thru' => $this->faker->date()
        ];
    }
}
