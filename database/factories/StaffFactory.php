<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Staff;
use App\Models\Vendor;
use App\Models\Country;
use App\values\UserStatusTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{

    protected $model = Staff::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'position' => $this->faker->jobTitle,
            'phone' => $this->faker->phoneNumber,
            'city' => $this->faker->city,
            'status' => $this->faker->randomElement([UserStatusTypes::ACTIVE, UserStatusTypes::INACTIVE]),
            'notes' => $this->faker->text(200),
            'address' => $this->faker->address,
            'vendor_id' => Vendor::factory(),
            'country_id' => Country::factory(),
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->optional()->date,
        ];
    }
}
