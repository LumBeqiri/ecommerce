<?php

// use App\Http\Controllers\Admin\Discount\DiscountController;
// use App\Models\Country;
// use App\Models\Currency;
// use App\Models\Discount;
// use App\Models\DiscountRule;
// use App\Models\Product;
// use App\Models\Region;
// use App\Models\TaxProvider;
// use App\Models\User;
// use App\values\DiscountRuleTypes;
// use Carbon\Carbon;
// use Database\Seeders\RoleAndPermissionSeeder;
// use Illuminate\Support\Facades\Bus;
// use Illuminate\Support\Facades\Notification;

// beforeEach(function () {
//     $this->seed(RoleAndPermissionSeeder::class);

//     Region::factory()->create();
//     Country::factory()->create();
//     Notification::fake();
//     Bus::fake();
// });

// it('can store percentage discount without conditions ', function () {
//     $user = User::factory()->create();
//     $user->assignRole('admin');
//     Currency::factory()->count(5)->create();
//     TaxProvider::factory()->create();
//     login($user);

//     $response = $this->postJson(action([DiscountController::class, 'store']),
//         [
//             'code' => 'LCX',
//             'discount_type' => 'percentage',
//             'region' => Region::first()->ulid,
//             'value' => 23.2,
//             'description' => 'hello',
//             'conditions' => 0,
//         ]
//     );

//     $response->assertStatus(200);

//     $discount_rule_ulid = $response->json('discount_rule.id');
//     $discount_code = $response->json('code');

//     $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
//     $this->assertDatabaseHas(DiscountRule::class, ['ulid' => $discount_rule_ulid]);
// });

// it('can store fixed discount without conditions ', function ($allocation) {
//     $user = User::factory()->create();
//     $user->assignRole('admin');

//     login($user);

//     $response = $this->postJson(action([DiscountController::class, 'store']),
//         [
//             'code' => 'LCX',
//             'discount_type' => DiscountRuleTypes::FIXED_AMOUNT,
//             'region' => Region::first()->ulid,
//             'value' => 23.2,
//             'allocation' => $allocation,
//             'description' => 'hello',
//             'conditions' => 0,
//         ]
//     );

//     $response->assertStatus(200);

//     $discount_rule_ulid = $response->json('discount_rule.id');
//     $discount_code = $response->json('code');

//     $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
//     $this->assertDatabaseHas(DiscountRule::class, ['ulid' => $discount_rule_ulid]);
// })->with([
//     'item_specific',
//     'total_amount',
// ]);

// it('can store free shipping discount without conditions ', function () {
//     $user = User::factory()->create();
//     $user->assignRole('admin');

//     login($user);

//     $response = $this->postJson(action([DiscountController::class, 'store']),
//         [
//             'code' => 'LCX',
//             'discount_type' => 'free_shipping',
//             'value' => 0,
//             'region' => Region::first()->ulid,
//             'description' => 'hello',
//             'conditions' => 0,
//         ]
//     );

//     $response->assertStatus(200);

//     $discount_rule_ulid = $response->json('discount_rule.id');
//     $discount_code = $response->json('code');

//     $this->assertDatabaseHas(Discount::class, ['code' => $discount_code]);
//     $this->assertDatabaseHas(DiscountRule::class, ['ulid' => $discount_rule_ulid]);
// });

// it('can update percentage discount', function () {
//     $user = User::factory()->create();
//     $user->assignRole('admin');

//     Product::factory()->count(5)->create();
//     DiscountRule::factory()->create();

//     $discount = Discount::factory()->create();
//     $usage_limit = $this->faker()->randomDigit();
//     $code = $this->faker()->word();
//     $description = $this->faker()->paragraph(1);
//     $value = $this->faker()->randomDigit();
//     $is_dynamic = $this->faker()->boolean(40);
//     $ends_at = Carbon::create(2023, 3, 23, 23, 59)->format('Y-m-d H:i:s');
//     $starts_at = Carbon::now()->format('Y-m-d H:i:s');
//     $region = Region::factory()->create();

//     login($user);

//     $response = $this->putJson(action([DiscountController::class, 'update'], $discount->ulid),
//         [
//             'code' => $code,
//             'discount_type' => 'percentage',
//             'region' => $region->ulid,
//             'value' => $value,
//             'description' => $description,
//             'usage_limit' => $usage_limit,
//             'starts_at' => $starts_at,
//             'ends_at' => $ends_at,
//             'is_dynamic' => $is_dynamic,
//         ]
//     );

//     $response->assertStatus(200);

//     expect($response->json('code'))
//         ->toBe($code);
//     expect($response->json('usage_limit'))
//         ->toBe($usage_limit);
//     expect($response->json('starts_at'))
//         ->not()->toBeNull();
//     expect($response->json('ends_at'))
//          ->not()->toBeNull();
//     expect($response->json('is_dynamic'))
//         ->toBe($is_dynamic);
// });

// it('can delete discount', function () {
//     $user = User::factory()->create();
//     $user->assignRole('admin');

//     Product::factory()->count(5)->create();
//     DiscountRule::factory()->create();

//     $discount = Discount::factory()->create();

//     login($user);

//     $response = $this->deleteJson(action([DiscountController::class, 'destroy'], $discount->ulid));

//     $response->assertStatus(200);

//     $this->assertDatabaseMissing(Discount::class, ['id' => $discount->id]);
// });
