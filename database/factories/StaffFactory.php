<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\User;
use App\Models\Vendor;
use App\values\UserStatusTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'ulid' => Str::ulid(),
            'user_id' => User::factory(),
            'position' => $this->faker->jobTitle,
            'status' => $this->faker->randomElement([UserStatusTypes::ACTIVE, UserStatusTypes::INACTIVE]),
            'notes' => $this->faker->text(200),
            'address' => $this->faker->address,
            'vendor_id' => Vendor::factory(),
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->optional()->date,
        ];
    }
}
