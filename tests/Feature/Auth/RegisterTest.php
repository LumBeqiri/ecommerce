<?php

use App\Models\Country;
use App\Models\Currency;
use App\Models\Region;
use App\Models\TaxProvider;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
});

it('can register user', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $password = $this->faker()->password(8, 12);

    $response = $this->post(route('register'), [
        'name' => $this->faker()->name(),
        'city' => $this->faker()->city(),
        'country_id' => Country::inRandomOrder()->first()->id,
        'zip' => $this->faker()->numberBetween(10000, 100000),
        'phone' => $this->faker()->phoneNumber(),
        'email' => $this->faker()->email(),
        'shipping_address' => $this->faker()->streetAddress(),
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertStatus(201);
});
