<?php

use App\Http\Controllers\Discount\DiscountController;
use App\Models\Country;
use App\Models\Discount;
use App\Models\DiscountCondition;
use App\Models\DiscountRule;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Database\Seeders\CurrencySeeder;

beforeEach(function () {
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
});

it('can store percentage discount without conditions ', function () {
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
            'conditions' => 0,
        ]
    );

    $response->assertStatus(200);

    $discount_rule_uuid = $response->json('discount_rule.id');
    $discount_code = $response->json('code');

    $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
    $this->assertDatabaseHas(DiscountRule::class, ['uuid' => $discount_rule_uuid]);
});

it('can store fixed discount without conditions ', function ($allocation) {
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
            'conditions' => 0,
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

it('can store free shipping discount without conditions ', function () {
    TaxProvider::factory()->create();
    $user = User::factory()->create();
    Region::factory()->create();
    login($user);

    $response = $this->postJson(action([DiscountController::class, 'store']),
        [
            'code' => 'LCX',
            'discount_type' => 'free_shipping',
            'amount' => 0,
            'regions' => [Region::first()->uuid],
            'description' => 'hello',
            'conditions' => 0,
        ]
    );

    $response->assertStatus(200);

    $discount_rule_uuid = $response->json('discount_rule.id');
    $discount_code = $response->json('code');

    $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
    $this->assertDatabaseHas(DiscountRule::class, ['uuid' => $discount_rule_uuid]);
});

it('can store percentage discount with conditions', function () {
    TaxProvider::factory()->create();
    $user = User::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Product::factory()->count(5)->create();
    login($user);

    $response = $this->postJson(action([DiscountController::class, 'store']),
        [
            'code' => 'LCX',
            'discount_type' => 'percentage',
            'regions' => [Region::first()->uuid],
            'percentage' => 23.2,
            'description' => 'hello',
            'conditions' => true,
            'operator' => 'in',
            'model_type' => 'product',
            'products' => Product::all()->pluck('uuid'),
        ]
    );

    $response->assertStatus(200);

    $discount_rule_uuid = $response->json('discount_rule.id');
    $discount_code = $response->json('code');
    $discount_uuid = $response->json('uuid');

    $discount_id = Discount::where('uuid', $discount_uuid)->firstOrFail()->value('id');
    $discount_rule_id = DiscountRule::where('uuid', $discount_rule_uuid)->firstOrFail()->value('id');

    $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
    $this->assertDatabaseHas(DiscountRule::class, ['uuid' => $discount_rule_uuid]);
    $this->assertDatabaseHas('discount_region', ['discount_id' => $discount_id]);
    $this->assertDatabaseHas(DiscountCondition::class, ['discount_rule_id' => $discount_rule_id]);

    $products = Product::all();
    foreach ($products as $product) {
        $this->assertDatabaseHas('discount_condition_product', ['product_id' => $product->id]);
    }
});
