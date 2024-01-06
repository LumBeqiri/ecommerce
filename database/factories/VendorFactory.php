<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'vendor_name' => $this->faker->company,
            'city' => $this->faker->city,
            'country_id' => Country::factory(),
            'user_id' => User::factory(),
            'status' => $this->faker->boolean,
            'approval_date' => $this->faker->optional()->date,
            'website' => $this->faker->optional()->url,
        ];
    }
}
