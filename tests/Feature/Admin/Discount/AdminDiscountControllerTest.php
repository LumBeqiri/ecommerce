<?php

namespace Tests\Feature\Admin\Discount;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Region;
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
use App\Http\Controllers\Admin\Discount\DiscountController;
use App\Http\Controllers\Admin\Discount\AdminDiscountController;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    Notification::fake();
    Bus::fake();
});

it('non-admin cannot access discount endpoints', function () {
    $user = User::factory()->create();
    $user->assignRole('buyer');
    login($user);

    $response = $this->getJson(action([AdminDiscountController::class, 'index']));
    $response->assertForbidden();
});

it('admin can list all discounts', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    login($admin);

    Discount::factory()->count(3)->create();

    $response = $this->getJson(action([AdminDiscountController::class, 'index']));

    $response->assertOk();
    $response->assertJsonCount(3, 'data');
});

it('can store percentage discount', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    login($admin);

    Currency::factory()->create();
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    Country::factory()->for($region)->create();

    $response = $this->postJson(
        action([AdminDiscountController::class, 'store']),
        [
            'code' => 'TEST25',
            'discount_type' => DiscountRuleTypes::PERCENTAGE,
            'region' => $region->ulid,
            'value' => 25.00,
            'description' => 'Test discount',
            'conditions' => false,
        ]
    );

    $response->assertOk();
    $response->assertJsonStructure([
        'code',
        'discount_rule' => [
            'id',
            'description',
            'discount_type',
            'value'
        ]
    ]);

    $this->assertDatabaseHas('discounts', [
        'code' => 'TEST25',
    ]);

    $this->assertDatabaseHas('discount_rules', [
        'discount_type' => DiscountRuleTypes::PERCENTAGE,
        'value' => 25.00,
    ]);
});

it('can store fixed amount discount', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    login($admin);

    Currency::factory()->create();
    TaxProvider::factory()->create();
    $region = Region::factory()->create();

    $response = $this->postJson(
        action([AdminDiscountController::class, 'store']),
        [
            'code' => 'FIXED10',
            'discount_type' => DiscountRuleTypes::FIXED_AMOUNT,
            'region' => $region->ulid,
            'value' => 1000, // $10.00 in cents
            'description' => 'Fixed amount discount',
            'allocation' => 'total_amount',
            'conditions' => false,
        ]
    );

    $response->assertOk();
    $this->assertDatabaseHas('discount_rules', [
        'discount_type' => DiscountRuleTypes::FIXED_AMOUNT,
        'value' => 1000,
        'allocation' => 'total_amount',
    ]);
});

it('can update discount', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    login($admin);

    $region = Region::factory()->create();
    $discountRule = DiscountRule::factory()
        ->withProducts(2)
        ->create();
    $discount = Discount::factory()->create([
        'discount_rule_id' => $discountRule->id
    ]);

    $response = $this->putJson(
        action([AdminDiscountController::class, 'update'], $discount),
        [
            'code' => 'UPDATED50',
            'region' => $region->ulid,
            'value' => 50.00,
            'description' => 'Updated description',
            'usage_limit' => 100,
            'starts_at' => now()->toDateTimeString(),
            'ends_at' => now()->addDays(30)->toDateTimeString(),
            'is_dynamic' => true,
        ]
    );

    $response->assertOk();
    
    $this->assertDatabaseHas('discounts', [
        'id' => $discount->id,
        'code' => 'UPDATED50',
        'usage_limit' => 100,
    ]);

    $this->assertDatabaseHas('discount_rules', [
        'id' => $discountRule->id,
        'value' => 50.00,
        'description' => 'Updated description',
    ]);
});

it('prevents duplicate discount codes', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    login($admin);

    $region = Region::factory()->create();
    $existingDiscount = Discount::factory()->create([
        'code' => 'EXISTING',
        'vendor_id' => $admin->id,
    ]);

    $response = $this->postJson(
        action([AdminDiscountController::class, 'store']),
        [
            'code' => 'EXISTING',
            'discount_type' => DiscountRuleTypes::PERCENTAGE,
            'region' => $region->ulid,
            'value' => 25.00,
            'description' => 'Test discount',
            'conditions' => false,
        ]
    );

    $response->assertStatus(422);
    $response->assertJsonFragment([
        'message' => 'Code EXISTING is already taken!'
    ]);
});

it('can delete discount', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    login($admin);

    $discountRule = DiscountRule::factory()
        ->withProducts(2)
        ->create();
    $discount = Discount::factory()->create([
        'discount_rule_id' => $discountRule->id
    ]);

    $response = $this->deleteJson(
        action([AdminDiscountController::class, 'destroy'], $discount)
    );

    $response->assertOk();
    $response->assertJsonFragment([
        'data' => 'Discount deleted successfully!'
    ]);

    $this->assertDatabaseMissing('discounts', ['id' => $discount->id]);
    $this->assertDatabaseMissing('discount_rules', ['id' => $discountRule->id]);
});

it('can show discount details', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    login($admin);

    $discountRule = DiscountRule::factory()
        ->withProducts(2)
        ->create();
    $discount = Discount::factory()->create([
        'discount_rule_id' => $discountRule->id
    ]);

    $response = $this->getJson(
        action([AdminDiscountController::class, 'show'], $discount)
    );

    $response->assertOk();
    $response->assertJsonStructure([
        'code',
        'is_dynamic',
        'starts_at',
        'ends_at',
        'discount_rule' => [
            'description',
            'discount_type',
            'value'
        ]
    ]);
}); 