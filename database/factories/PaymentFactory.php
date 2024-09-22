<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\PaymentProcessor;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ulid' => Str::ulid(),
            'amount' => $this->faker->numberBetween(100, 1000),
            'currency_id' => Currency::factory(),
            'payment_processor_id' => PaymentProcessor::factory(),
            'vendor_id' => Vendor::factory(),
        ];
    }
}
