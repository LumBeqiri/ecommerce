<?php

namespace Database\Factories;

use App\Models\DiscountRule;
use App\values\DiscountAllocationTypes;
use App\values\DiscountRuleTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountRuleFactory extends Factory
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
            'description' => $this->faker->paragraph(1),
            'discount_type' => $type = $this->faker->randomElement([DiscountRuleTypes::FIXED_AMOUNT, DiscountRuleTypes::PERCENTAGE, DiscountRuleTypes::FREE_SHIPPING]),
            'value' => $this->valueForType($type),
            'region_id' => 1,
            'allocation' => $this->faker->randomElement([DiscountAllocationTypes::TOTAL_AMOUNT, DiscountAllocationTypes::ITEM_SPICIFIC]),
        ];
    }

    private function valueForType($type)
    {
        if ($type === DiscountRuleTypes::FIXED_AMOUNT) {
            $this->faker->numberBetween(1, 50);
        }
        if ($type === DiscountRuleTypes::FREE_SHIPPING) {
            return 0;
        }
        if ($type === DiscountRuleTypes::PERCENTAGE) {
            return $this->faker->numberBetween(1, 50);
        }

        return 0;
    }

    // public function configure()
    // {
    //     return $this->afterCreating(function (DiscountRule $discount_rule) {
    //         $discount_rule->region_id = 1;
    //     });
    // }
}
