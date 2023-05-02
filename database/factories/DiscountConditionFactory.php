<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiscountCondition>
 */
class DiscountConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'model_type' => 'product',
            'operator' => $this->faker->randomElement(['in', 'not_in']),
            'discount_rule_id' => $this->faker->randomNumber(1, 10),
        ];
    }
}
