<?php

use App\Http\Controllers\CustomerGroup\CustomerGroupController;
use App\Models\User;
use App\Models\Region;
use App\Models\Seller;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\TaxProvider;
use Database\Seeders\CurrencySeeder;
use App\Http\Controllers\Seller\SellerProductController;
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

    // $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson(action([CustomerGroupController::class, 'store']),
        [
          'name' => $customer_group_name,
          'metadata' => '{"info": "hello"}'
        ]
    );

    dd($response->json());
    $response->assertStatus(200);

    $this->assertDatabaseHas(CustomerGroup::class, ['name' => $customer_group_name]);

});

