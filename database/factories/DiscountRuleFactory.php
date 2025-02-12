<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\DiscountRule;
use App\Models\Region;
use App\values\DiscountRuleTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountRuleFactory extends Factory
{
    protected $model = DiscountRule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $region = Region::factory()->create();
        $isPercentage = $this->faker->boolean();

        return [
            'ulid' => Str::ulid(),
            'description' => $this->faker->sentence(),
            'region_id' => $region->id,
            'currency_id' => $isPercentage ? null : ($region->currency_id ?? Currency::factory()->create()->id),
            'discount_type' => $isPercentage ? DiscountRuleTypes::PERCENTAGE : DiscountRuleTypes::FIXED_AMOUNT,
            'value' => $isPercentage ? $this->faker->numberBetween(1, 100) : $this->faker->numberBetween(500, 10000),
            'allocation' => null,
            'operator' => 'in',
            'metadata' => null,
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
