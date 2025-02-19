<?php

use App\Mail\UserPasswordChanged;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Notification::fake();
    Bus::fake();
});

it('can change password', function () {
    Mail::fake();

    $password = '123123123';
    $user = User::factory()->create([
        'password' => bcrypt($password),
    ]);

    login($user);

    $response = $this->putJson(route('change_password', [
        'old_password' => $password,
        'new_password' => 'new_password',
    ]));

    $response->assertOk();

    $new_login_response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'new_password',
    ]);

    $new_login_response->assertOk();

    Mail::assertQueued(UserPasswordChanged::class);
});
