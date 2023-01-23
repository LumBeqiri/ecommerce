<?php

use App\Http\Controllers\CustomerGroup\CustomerGroupController;
use App\Models\User;
use Database\Seeders\CurrencySeeder;
use App\Models\CustomerGroup;

beforeEach(function(){
    $this->seed(CurrencySeeder::class); 
    Notification::fake();
    Bus::fake();
});

it('can show all user groups for seller', function(){
    $user = User::factory()->create();
    User::factory()->count(10)->create();
    CustomerGroup::factory()
        ->for($user)
        ->count(5)->create();

    login($user);

    $response = $this->getJson(action([CustomerGroupController::class, 'index']));

    $response->assertOk();
});

it('can store a customer group ', function(){

    $user = User::factory()->create();
    
    $customer_group_name = 'Golf Club';

    login($user);

    $response = $this->postJson(action([CustomerGroupController::class, 'store']),
        [
          'name' => $customer_group_name,
          'metadata' => '{"info": "hello"}'
        ]
    );

    $response->assertStatus(200);

    $this->assertDatabaseHas(CustomerGroup::class, ['name' => $customer_group_name]);

});


it('can show one coustomer group', function(){
    User::factory()->count(5)->create();
    CustomerGroup::factory()->count(5)->create();

    $user = User::factory()->create();

    $customerGroup = CustomerGroup::factory()->for($user)->create();
    
    login($user);

    $response = $this->getJson(action([CustomerGroupController::class, 'show'], $customerGroup->uuid));

    $response->assertOk();
});


it('can not show customer group of another seller',function(){
    $user = User::factory()->create();
    User::factory()->count(10)->create();
    $customerGroup = CustomerGroup::factory()
    ->for($user)->create();
    $userThatDoesntOwnCustomerGroups = User::factory()->create();

    login($userThatDoesntOwnCustomerGroups);

    $response = $this->getJson(action([CustomerGroupController::class, 'show'],$customerGroup->uuid));

    $response->assertStatus(403);
});

