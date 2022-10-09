<?php

namespace Database\Factories;

use App\Models\Buyer;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        static $password;
        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            // 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'password' => $password ?: $password = bcrypt('secret'),
            'city' => $this->faker->city(),
            'state' => $this->faker->country(),
            'zip' => 5000,
            'shipping_address' => $this->faker->address(),
            'phone' => "044123456",
            'remember_token' => Str::random(10),
            'verified' =>  $this->faker->randomElement([User::VERIFIED_USER,User::UNVERIFIED_USER]),
            'verification_token' => User::VERIFIED_USER ? null : User::generateVerificationCode(),
            'admin' => $this->faker->randomElement([User::ADMIN_USER,User::REGULAR_USER]),

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
}