<?php

use App\Models\Country;
use App\Models\Currency;
use App\Models\Region;
use App\Models\TaxProvider;
use function Pest\Faker\faker;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
});

it('can register user', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $password = faker()->password(8, 12);

    $response = $this->post(route('register'), [
        'name' => faker()->name(),
        'city' => faker()->city(),
        'country_id' => Country::inRandomOrder()->first()->id,
        'zip' => faker()->numberBetween(10000, 100000),
        'phone' => faker()->phoneNumber(),
        'email' => faker()->email(),
        'shipping_address' => faker()->streetAddress(),
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertStatus(201);
});
