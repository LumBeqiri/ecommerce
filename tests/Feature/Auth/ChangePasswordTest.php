<?php

use App\Models\User;

it('can change password', function () {

    $password = '123123123';
    $user = User::factory()->create([
        'password' => bcrypt($password)
    ]);


    login($user);

    $response = $this->putJson(route('change_password', [
        'old_password' => $password,
        'new_password' => 'new_password'
    ]));

    $response->assertOk();

    $new_login_response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'new_password'
    ]);

    $new_login_response->assertOk();


});
