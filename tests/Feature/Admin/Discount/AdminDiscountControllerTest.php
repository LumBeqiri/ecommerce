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

test('non-admin cannot access discount endpoints', function () {
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
});

it('can update discount', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    login($admin);

    $region = Region::factory()->create();
    $discountRule = DiscountRule::factory()
        ->create();
    $discount = Discount::factory()
    ->withProducts(2)
    ->create([
        'discount_rule_id' => $discountRule->id
    ]);

    $response = $this->putJson(
        action([AdminDiscountController::class, 'update'], $discount),
        [
            'code' => 'UPDATED50',
            'region_id' => $region->ulid,
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


it('can delete discount', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    login($admin);

    $discountRule = DiscountRule::factory()
        ->create();
    $discount = Discount::factory()
    ->withProducts(2)
    ->create([
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
        ->create();
    $discount = Discount::factory()
    ->withProducts(2)
    ->create([
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