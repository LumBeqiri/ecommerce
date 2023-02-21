<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
});

it('can reset password', function () {
    Mail::fake();
    Notification::fake();

    $user = User::factory()->create();

    //check if password reset notification is sent to user
    $reset_link_response = $this->postJson(
        route('reset.link'), [
            'email' => $user->email,
        ]);

    $reset_link_response->assertStatus(302);
    Notification::assertSentTo($user, ResetPassword::class);

    // a token is needed to reset password
    $token = Password::createToken($user);

    // send a request to change password
    $reset_response = $this->postJson(
        route('password.reset'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
        ]
    );

    $reset_response->assertStatus(200);

    $new_login_response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'testtest',
    ]);

    //check if new password works
    $new_login_response->assertOk();
});
