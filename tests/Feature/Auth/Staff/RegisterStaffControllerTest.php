<?php

use App\Models\Country;
use App\Models\Currency;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Vendor;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
    $this->seed(RoleAndPermissionSeeder::class);

});

test('vendor can register staff user', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $password = $this->faker()->password(8, 12);

    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create([
        'user_id' => $vendorUser->id,
    ]);

    $vendorUser->assignRole('vendor');

    login($vendorUser);

    $password = $this->faker()->password(8, 12);

    $response = $this->post(route('register-staff'), [
        'first_name' => $this->faker()->firstName(),
        'last_name' => $this->faker()->lastName(),
        'position' => $this->faker()->jobTitle(),
        'phone' => $this->faker()->phoneNumber(),
        'city' => $this->faker()->city(),
        'status' => $this->faker()->randomElement(['active', 'inactive']), // Assuming 'status' can be either 'active' or 'inactive'
        'notes' => $this->faker()->optional()->text(500),
        'address' => $this->faker()->streetAddress(),
        'vendor_id' => $vendor->id,
        'country_id' => Country::inRandomOrder()->first()->id,
        'start_date' => null,
        'end_date' => null,
        'role' => $this->faker()->randomElement(['employee', 'manager']),
        'email' => $this->faker()->email(),
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertStatus(201);
})->todo();
