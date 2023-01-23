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
    User::factory()->count(5)->create();
    CustomerGroup::factory()
        ->for($user)
        ->count(5)->create();

    login($user);

    $response = $this->getJson(action([CustomerGroupController::class, 'index']));

    $response->assertOk();
});

it('can store a customer group ', function(){

    $user = User::factory()->create();
    
    $customerGroupName = 'Golf Club';

    login($user);

    $response = $this->postJson(action([CustomerGroupController::class, 'store']),
        [
          'name' => $customerGroupName,
          'metadata' => '{"info": "hello"}'
        ]
    );

    $response->assertStatus(200);

    $this->assertDatabaseHas(CustomerGroup::class, ['name' => $customerGroupName]);

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
    User::factory()->count(2)->create();
    $customerGroup = CustomerGroup::factory()
    ->for($user)->create();
    $userThatDoesntOwnCustomerGroups = User::factory()->create();

    login($userThatDoesntOwnCustomerGroups);

    $response = $this->getJson(action([CustomerGroupController::class, 'show'],$customerGroup->uuid));

    $response->assertStatus(403);
});



it('can delete one coustomer group', function(){
    User::factory()->create();
    CustomerGroup::factory()->create();

    $user = User::factory()->create();

    $customerGroup = CustomerGroup::factory()->for($user)->create();
    
    login($user);

    $response = $this->deleteJson(action([CustomerGroupController::class, 'destroy'], $customerGroup->uuid));

    $response->assertOk();

    $this->assertDatabaseMissing(CustomerGroup::class, ['id' => $customerGroup->id]);
});



it('can not delete customer group of another seller',function(){
    $user = User::factory()->create();
    User::factory()->count(2)->create();
    $customerGroup = CustomerGroup::factory()
    ->for($user)->create();
    $userThatDoesntOwnCustomerGroups = User::factory()->create();

    login($userThatDoesntOwnCustomerGroups);

    $response = $this->deleteJson(action([CustomerGroupController::class, 'destroy'],$customerGroup->uuid));

    $response->assertStatus(403);

    $this->assertDatabaseHas(CustomerGroup::class, ['id' => $customerGroup->id]);
});
