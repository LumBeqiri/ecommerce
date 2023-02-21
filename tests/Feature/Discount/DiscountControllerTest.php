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
use Carbon\Carbon;
use Database\Seeders\CurrencySeeder;
use function Pest\Faker\faker;

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
            'value' => 23.2,
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
            'value' => 23.2,
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
            'value' => 0,
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
            'value' => 23.2,
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

it('can update percentage discount', function () {
    TaxProvider::factory()->create();
    $user = User::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Product::factory()->count(5)->create();
    DiscountRule::factory()->create();

    $discount = Discount::factory()->create();
    $usage_limit = faker()->randomDigit();
    $code = faker()->word();
    $description = faker()->paragraph(1);
    $value = faker()->randomDigit();
    $is_dynamic = faker()->boolean(40);
    $ends_at = Carbon::create(2023, 3, 23, 23, 59)->format('Y-m-d H:i:s');
    $starts_at = Carbon::now()->format('Y-m-d H:i:s');
    $region = Region::factory()->create();

    login($user);

    $response = $this->putJson(action([DiscountController::class, 'update'], $discount->uuid),
        [
            'code' => $code,
            'discount_type' => 'percentage',
            'regions' => [$region->uuid],
            'value' => $value,
            'description' => $description,
            'usage_limit' => $usage_limit,
            'starts_at' => $starts_at,
            'ends_at' => $ends_at,
            'is_dynamic' => $is_dynamic,
        ]
    );

    $response->assertStatus(200);

    expect($response->json('code'))
        ->toBe($code);
    expect($response->json('usage_limit'))
        ->toBe($usage_limit);
    expect($response->json('starts_at'))
        ->not()->toBeNull(null);
    expect($response->json('ends_at'))
         ->not()->toBeNull(null);
    expect($response->json('is_dynamic'))
        ->toBe($is_dynamic);

    $this->assertDatabaseHas('discount_region', ['discount_id' => $discount->id, 'region_id' => $region->id]);
});

it('can delete discount', function () {
    TaxProvider::factory()->create();
    $user = User::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Product::factory()->count(5)->create();
    DiscountRule::factory()->create();

    $discount = Discount::factory()->create();

    login($user);

    $response = $this->deleteJson(action([DiscountController::class, 'destroy'], $discount->uuid));

    $response->assertStatus(200);

    $this->assertDatabaseMissing(Discount::class, ['id' => $discount->id]);
});
