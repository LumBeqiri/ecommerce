<?php

use App\Models\User;

beforeEach(function(){
    Notification::fake();
    Bus::fake();

});

it('can login user', function () {
    $password = Str::random();

    $user = User::factory()->create(['password' => bcrypt($password)]);

    $response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => $password
    ]);

    $response->assertOk();
});
