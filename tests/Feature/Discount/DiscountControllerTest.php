<?php

use App\Models\User;
use App\Models\Discount;
use App\Models\DiscountRule;
use App\Http\Controllers\Discount\DiscountController;

beforeEach(function(){
    Notification::fake();
    Bus::fake();
});

it('can store discount', function(){
    $user = User::factory()->create();

    login($user);

    $response = $this->postJson(action([DiscountController::class, 'store']),
      [
        'code' => 'LCX',
        'discount_type' => 'percentage',
        'allocation' => 'item_specific',
        'percentage' => 23.2,
        'description' => 'hello',
        'conditions' => false,
      ]
    );

    $response->assertStatus(200);

    dd($response->json());

    $discount_rule_uuid = $response->json('discount_rule.id');
    $discount_code = $response->json('code');

    $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
    $this->assertDatabaseHas(DiscountRule::class, ['uuid' => $discount_rule_uuid]);

});