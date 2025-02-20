<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Discount;
use App\Models\TaxProvider;
use App\Models\DiscountRule;
use App\values\DiscountRuleTypes;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\User\Discount\UserDiscountController;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);

    Region::factory()->create();
    Country::factory()->create();
    Notification::fake();
    Bus::fake();
});

it('can store percentage discount without conditions ', function () {
    $vendor = Vendor::factory()->create();
    $vendor->user->assignRole('vendor');
    Currency::factory()->create();
    TaxProvider::factory()->create();
    login($vendor->user);

    $response = $this->postJson(action([UserDiscountController::class, 'store']),
        [
            'code' => 'LCX',
            'discount_type' => 'percentage',
            'region_id' => Region::first()->ulid,
            'value' => 23.2,
            'description' => 'hello',
            'conditions' => 0,
        ]
    );

    $response->assertStatus(200);

    $discount_rule_ulid = $response->json('discount_rule.id');
    $discount_code = $response->json('code');

    $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
    $this->assertDatabaseHas(DiscountRule::class, ['ulid' => $discount_rule_ulid]);
});

it('can store fixed discount without conditions ', function ($allocation) {
    $vendor = Vendor::factory()->create();
    $vendor->user->assignRole('vendor');

    login($vendor->user);

    $response = $this->postJson(action([UserDiscountController::class, 'store']),
        [
            'code' => 'LCX',
            'discount_type' => DiscountRuleTypes::FIXED_AMOUNT,
            'region_id' => Region::first()->ulid,
            'value' => 23.2,
            'allocation' => $allocation,
            'description' => 'hello',
            'conditions' => 0,
        ]
    );

    $response->assertStatus(200);

    $discount_rule_ulid = $response->json('discount_rule.id');
    $discount_code = $response->json('code');

    $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
    $this->assertDatabaseHas(DiscountRule::class, ['ulid' => $discount_rule_ulid]);
})->with([
    'item_specific',
    'total_amount',
]);

it('can store free shipping discount without conditions ', function () {
    $vendor = Vendor::factory()->create();
    $vendor->user->assignRole('vendor');

    login($vendor->user);

    $response = $this->postJson(action([UserDiscountController::class, 'store']),
        [
            'code' => 'LCX',
            'discount_type' => 'free_shipping',
            'value' => 0,
            'region_id' => Region::first()->ulid,
            'description' => 'hello',
            'conditions' => 0,
        ]
    );

    $response->assertStatus(200);

    $discount_rule_ulid = $response->json('discount_rule.id');
    $discount_code = $response->json('code');

    $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
    $this->assertDatabaseHas(DiscountRule::class, ['ulid' => $discount_rule_ulid]);
});

it('can update percentage discount', function () {
    $vendor = Vendor::factory()->create();
    $vendor->user->assignRole('vendor');

  

    Product::factory()->count(5)->create();
    DiscountRule::factory()->create();

    $discount = Discount::factory()->create([
        'vendor_id' => $vendor->id
    ]);
    $usage_limit = $this->faker()->randomDigit();
    $code = $this->faker()->word();
    $description = $this->faker()->paragraph(1);
    $value = $this->faker()->randomDigit();
    $is_dynamic = $this->faker()->boolean(40);
    $ends_at = Carbon::create(2023, 3, 23, 23, 59)->format('Y-m-d H:i:s');
    $starts_at = Carbon::now()->format('Y-m-d H:i:s');
    $region = Region::factory()->create();
    
    login($vendor->user);


    $response = $this->putJson(action([UserDiscountController::class, 'update'], $discount->ulid),
        [
            'code' => $code,
            'discount_type' => 'percentage',
            'region_id' => $region->ulid,
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
        ->not()->toBeNull();
    expect($response->json('ends_at'))
         ->not()->toBeNull();
    expect($response->json('is_dynamic'))
        ->toBe($is_dynamic);
});

it('can delete discount', function () {
    $vendor = Vendor::factory()->create();
    $vendor->user->assignRole('vendor');

    Product::factory()->count(5)->create();
    DiscountRule::factory()->create();
    
    $discount = Discount::factory()->create([
        'vendor_id' => $vendor->id
    ]);
    
    login($vendor->user);
    

    $response = $this->deleteJson(action([UserDiscountController::class, 'destroy'], $discount->ulid));

    $response->assertStatus(200);

    $this->assertDatabaseMissing(Discount::class, ['id' => $discount->id]);
});
