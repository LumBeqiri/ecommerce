<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\DiscountRule;
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
            'code' => $this->faker->word,
            'is_dynamic' => $this->faker->boolean(60),
            'is_disabled' => $this->faker->boolean(60),
            'discount_rule_id' => DiscountRule::all()->random()->id,
            'starts_at' => $this->faker->dateTime(),
            'ends_at' => $this->faker->dateTime(),
            'usage_limit' => $this->faker->numberBetween(2,20),
            'usage_limit' => $this->faker->numberBetween(2,20),
            'usage_count' => $this->faker->numberBetween(2,20),
            'parent_id' => null
        ];
    }
}
