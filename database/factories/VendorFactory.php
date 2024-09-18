<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'ulid' => Str::ulid(),
            'vendor_name' => $this->faker->company,
            'city' => $this->faker->city,
            'country_id' => Country::factory(),
            'user_id' => User::factory(),
            'status' => $this->faker->boolean,
            'approval_date' => $this->faker->date,
            'website' => $this->faker->url,
        ];
    }
}
