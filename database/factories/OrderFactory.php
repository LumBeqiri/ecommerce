<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'uuid' => $this->faker->uuid(),
            'buyer_id' => User::all()->random()->id,
            'shipping_name' => $this->faker->name(),
            'shipping_address' => $this->faker->address(),
            'shipping_city' => $this->faker->city(),
            'shipping_country' => $this->faker->country(),
            'order_tax' => $this->faker->numberBetween($min = 1, $max = 100),
            'total' => $this->faker->numberBetween($min = 1, $max = 100),
            'order_date' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
            'order_shipped' => $order_shipped = $this->faker->randomElement([Order::SHIPPED_ORDER, Order::UNSHIPPED_ORDER]),
            'order_email' => $this->faker->unique()->safeEmail(),
            'order_phone' => '044123456',
            'payment_id' => $this->faker->randomDigit(),
            'currency_id' => Currency::all()->random()->id,
        ];
    }
}
