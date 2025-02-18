<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\values\OrderStatusTypes;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Order::class;

    public function definition()
    {
        return [
            'ulid' => Str::ulid(),
            'buyer_id' => User::factory(),
            'shipping_name' => $this->faker->name(),
            'shipping_address' => $this->faker->address(),
            'shipping_city' => $this->faker->city(),
            'shipping_country_id' => Country::factory(),
            'tax_rate' => $this->faker->randomFloat(2, 0, 20),
            'tax_total' => $this->faker->numberBetween(100, 1000),
            'total' => $this->faker->numberBetween(1000, 10000),
            'currency_id' => Currency::factory(),
            'ordered_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'shipped_at' => $this->faker->optional()->dateTimeBetween('-1 years', 'now'),
            'order_email' => $this->faker->unique()->safeEmail(),
            'order_phone' => $this->faker->phoneNumber(),
            'payment_id' => Payment::factory(),
            'status' => $this->faker->randomElement(OrderStatusTypes::cases()),
        ];
    }
}
