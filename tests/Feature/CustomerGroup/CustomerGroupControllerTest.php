<?php

use App\Http\Controllers\CustomerGroup\CustomerGroupController;
use App\Models\Country;
use App\Models\Currency;
use App\Models\CustomerGroup;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Currency::factory()->count(5)->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Notification::fake();
    Bus::fake();
});

it('can show all user groups for seller', function () {
    $user = User::factory()->create();
    User::factory()->count(5)->create();
    CustomerGroup::factory()
        ->for($user)
        ->count(5)->create();

    login($user);

    $response = $this->getJson(action([CustomerGroupController::class, 'index']));

    $response->assertOk();
})->todo();

it('can store a customer group ', function () {
    $user = User::factory()->create();
    $users = User::factory()->count(10)->create();

    $customerGroupName = 'Golf Club';

    login($user);

    $response = $this->postJson(action([CustomerGroupController::class, 'store']),
        [
            'name' => $customerGroupName,
            'metadata' => '{"info": "hello"}',
            'users' => $users->pluck('uuid'),
        ]
    );

    $response->assertStatus(200);

    $this->assertDatabaseHas(CustomerGroup::class, ['name' => $customerGroupName]);
})->todo();

it('can show one coustomer group', function () {
    User::factory()->count(5)->create();
    CustomerGroup::factory()->count(5)->create();

    $user = User::factory()->create();

    $customerGroup = CustomerGroup::factory()->for($user)->create();

    login($user);

    $response = $this->getJson(action([CustomerGroupController::class, 'show'], $customerGroup->uuid));

    $response->assertOk();
})->todo();

it('can not show customer group of another seller', function () {
    $user = User::factory()->create();
    User::factory()->count(2)->create();
    $customerGroup = CustomerGroup::factory()
        ->for($user)->create();
    $userThatDoesntOwnCustomerGroups = User::factory()->create();

    login($userThatDoesntOwnCustomerGroups);

    $response = $this->getJson(action([CustomerGroupController::class, 'show'], $customerGroup->uuid));

    $response->assertStatus(403);
})->todo();

it('can delete one coustomer group', function () {
    User::factory()->create();
    CustomerGroup::factory()->create();

    $user = User::factory()->create();

    $customerGroup = CustomerGroup::factory()->for($user)->create();

    login($user);

    $response = $this->deleteJson(action([CustomerGroupController::class, 'destroy'], $customerGroup->uuid));

    $response->assertOk();

    $this->assertDatabaseMissing(CustomerGroup::class, ['id' => $customerGroup->id]);
})->todo();

it('can not delete customer group of another seller', function () {
    $user = User::factory()->create();
    User::factory()->count(2)->create();
    $customerGroup = CustomerGroup::factory()
        ->for($user)->create();
    $userThatDoesntOwnCustomerGroups = User::factory()->create();

    login($userThatDoesntOwnCustomerGroups);

    $response = $this->deleteJson(action([CustomerGroupController::class, 'destroy'], $customerGroup->uuid));

    $response->assertStatus(403);

    $this->assertDatabaseHas(CustomerGroup::class, ['id' => $customerGroup->id]);
})->todo();
