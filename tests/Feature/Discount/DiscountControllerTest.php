<?php

use App\Http\Controllers\Discount\DiscountController;
use App\Models\Discount;
use App\Models\DiscountRule;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Database\Seeders\CurrencySeeder;

beforeEach(function () {
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
});

it('can store percentage discount for percentage type without conditions ', function () {
    TaxProvider::factory()->create();
    $user = User::factory()->create();
    Region::factory()->create();
    login($user);

    $response = $this->postJson(action([DiscountController::class, 'store']),
        [
            'code' => 'LCX',
            'discount_type' => 'percentage',
            'regions' => [Region::first()->uuid],
            'percentage' => 23.2,
            'description' => 'hello',
            'conditions' => false,
        ]
    );

    $response->assertStatus(200);

    $discount_rule_uuid = $response->json('discount_rule.id');
    $discount_code = $response->json('code');

    $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
    $this->assertDatabaseHas(DiscountRule::class, ['uuid' => $discount_rule_uuid]);
});

it('can store fixed discount for fixed amount type without conditions ', function ($allocation) {
    TaxProvider::factory()->create();
    $user = User::factory()->create();
    Region::factory()->create();
    login($user);

    $response = $this->postJson(action([DiscountController::class, 'store']),
        [
            'code' => 'LCX',
            'discount_type' => 'fixed',
            'regions' => [Region::first()->uuid],
            'amount' => 23.2,
            'allocation' => $allocation,
            'description' => 'hello',
            'conditions' => false,
        ]
    );

    $response->assertStatus(200);

    $discount_rule_uuid = $response->json('discount_rule.id');
    $discount_code = $response->json('code');

    $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
    $this->assertDatabaseHas(DiscountRule::class, ['uuid' => $discount_rule_uuid]);
})->with([
    'item_specific',
    'total_amount',
]);
