<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\{Order,Buyer,Product, Seller};
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

        $seller = Seller::has('products')->get()->random();

        $buyer = User::all()->except($seller->id)->random();


    
        return [
            'uuid' => $this->faker->uuid(),
            'buyer_id' => $buyer->id,
            'ship_name'=> $this->faker->name(),
            'ship_address' => $this->faker->address(),
            'ship_city' => $this->faker->city(),
            'ship_state' => $this->faker->country(),
            'order_tax' => $this->faker->numberBetween($min = 1, $max = 100),
            'total' => $this->faker->numberBetween($min = 1, $max = 100),
            'order_date' => $this->faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
            'order_shipped' => $order_shipped = $this->faker->randomElement([Order::SHIPPED_ORDER,Order::UNSHIPPED_ORDER]),
            'order_email' => $this->faker->unique()->safeEmail(),
            'order_phone' => "044123456",
            'payment_id' => $this->faker->randomDigit()
        ];
    }
}
