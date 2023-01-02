<?php

use function Pest\Faker\faker;

beforeEach(function(){
    Notification::fake();
    Bus::fake();

});


it('can register user', function () {

    $password = faker()->password(8,12);
    
    $response = $this->post(route('register'),[
        'name' => faker()->name(),
        'city' => faker()->city(),
        'state' => faker()->country(),
        'zip' => faker()->numberBetween(10000,100000),
        'phone' => faker()->phoneNumber(),
        'email'=> faker()->email(),
        'shipping_address'=> faker()->streetAddress(),
        'password' => $password,
        'password_confirmation' => $password
    ]);

    $response->assertStatus(201);

});




