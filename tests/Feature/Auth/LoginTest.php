<?php

use App\Models\Country;
use App\Models\Currency;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

// beforeEach(function () {
//     Currency::factory()->create();
//     TaxProvider::factory()->create();
//     Region::factory()->create();
//     Country::factory()->create();
//     Notification::fake();
//     Bus::fake();
// });

// it('can login user', function () {
//     $password = Str::random();

//     $user = User::factory()->create(['password' => bcrypt($password)]);

//     $response = $this->postJson(route('login'), [
//         'email' => $user->email,
//         'password' => $password,
//     ]);

//     $response->assertOk();
// });
