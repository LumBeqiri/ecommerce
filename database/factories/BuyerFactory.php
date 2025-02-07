<?php

namespace Database\Factories;

use App\Models\Buyer;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BuyerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Buyer::class;

    public function definition()
    {

        return [
            'ulid' => Str::ulid(),
            'shipping_address' => $this->faker->address,
            'user_id' => User::all()->random()->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function configure()
    {
        return $this->afterCreating(function (Buyer $buyer) {
            $buyer->user->user_settings()->create([

                'phone' => $this->faker->phoneNumber,
                'city' => $this->faker->city,
                'country_id' => Country::inRandomOrder()->first()->id,
                'zip' => $this->faker->postcode,
                'user_id' => $buyer->user_id,

            ]);
        });
    }
}
