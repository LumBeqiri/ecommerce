<?php

use App\Http\Controllers\CustomerGroup\CustomerGroupController;
use App\Models\User;
use Database\Seeders\CurrencySeeder;
use App\Models\CustomerGroup;

use function Pest\Laravel\json;

beforeEach(function(){
    $this->seed(CurrencySeeder::class); 
    Notification::fake();
    Bus::fake();
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

