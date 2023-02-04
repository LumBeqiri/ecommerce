<?php

use App\Http\Controllers\Discount\DiscountController;
use App\Models\User;

beforeEach(function(){
    Notification::fake();
    Bus::fake();
});

it('can store discount', function(){
    $user = User::factory()->create();

    login($user);

    $response = $this->postJson(action([DiscountController::class, 'store']),
    [
      'discount_type' => 'percentage',
      'allocation' => 'item_specific',
      'percentage' => 23.2,
      'description' => 'hello',
    ]
);

$response->assertStatus(201);

});