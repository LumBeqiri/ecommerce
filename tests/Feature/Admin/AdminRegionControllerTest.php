<?php

use App\Models\User;
use App\Models\Region;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\Admin\Region\AdminRegionController;
use App\Models\Country;
use App\Models\Currency;
use App\Models\TaxProvider;
use Database\Seeders\CountrySeeder;

use function Pest\Faker\faker;

beforeEach(function(){
    Notification::fake();
    Bus::fake();
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
    $this->seed(CountrySeeder::class);
});

it('admin can show regions', function(){
    TaxProvider::factory()->create();
    $user = User::factory()->create();
    $user->assignRole('admin');
    Region::factory()->count(1)->make();

    login($user);

    $response = $this->getJson(action([AdminRegionController::class,'index']));
    $response->assertOk();

});


it('admin can show region', function(){
    TaxProvider::factory()->create();
    $user = User::factory()->create();
    $user->assignRole('admin');
    $title = faker()->word();
    $region = Region::factory()->create(['title' => $title]);

    login($user);

    $response = $this->getJson(action([AdminRegionController::class,'show'],$region->uuid));
    $response->assertOk();

    expect($response->json('title'))->toBe($title);

});


it('admin can create region', function(){
    $tax_provider = TaxProvider::factory()->create(['id' => 4]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    login($user);

    $title = 'EU';

    $response = $this->postJson(action([AdminRegionController::class,'store']),[
        'title' => $title,
        'currency_id' => Currency::all()->random()->id,
        'tax_rate' => 22,
        'tax_code' => 'JIIL232',
        'tax_provider_id' => $tax_provider->id
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Region::class, ['title' => $title]);
});


it('admin can update region title', function(){
    TaxProvider::factory()->create(['id' => 4]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $old_attr = 'old_title';
    $new_attr = 'new_title';
    $region = Region::factory()->create(['title' => $old_attr]);
    login($user);

    $response = $this->putJson(action([AdminRegionController::class,'update'], $region->uuid),[
        'title' => $new_attr
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Region::class, ['title' => $new_attr, 'id' => $region->id ]);
});


it('admin can update region currency', function(){
    TaxProvider::factory()->create(['id' => 4]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $old_attr = Currency::find(1);
    $new_attr = Currency::find(2);
   
    $region = Region::factory()->create(['currency_id' => $old_attr->id]);
    login($user);

    $response = $this->putJson(action([AdminRegionController::class,'update'], $region->uuid),[
        'currency_id' => $new_attr->id
    ]);


    $response->assertOk();

    $this->assertDatabaseHas(Region::class, ['currency_id' => $new_attr->id, 'id' => $region->id ]);
});



it('admin can update region tax rate', function(){
    TaxProvider::factory()->create(['id' => 4]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $old_attr = 18;
    $new_attr = 22;
   
    $region = Region::factory()->create(['tax_rate' => $old_attr]);
    login($user);

    $response = $this->putJson(action([AdminRegionController::class,'update'], $region->uuid),[
        'tax_rate' => $new_attr
    ]);


    $response->assertOk();

    $this->assertDatabaseHas(Region::class, ['tax_rate' => $new_attr, 'id' => $region->id ]);
});


it('admin can update region tax code', function(){
    TaxProvider::factory()->create(['id' => 4]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $old_attr = 'TESTCODE';
    $new_attr = 'UPDATETESTCODE';
   
    $region = Region::factory()->create(['tax_code' => $old_attr]);
    login($user);

    $response = $this->putJson(action([AdminRegionController::class,'update'], $region->uuid),[
        'tax_code' => $new_attr
    ]);


    $response->assertOk();

    $this->assertDatabaseHas(Region::class, ['tax_code' => $new_attr, 'id' => $region->id ]);
});


it('admin can update region tax provider', function(){
    $old_attr = TaxProvider::factory()->create(['id' => 1]);
    $new_attr = TaxProvider::factory()->create(['id' => 2]);

    $user = User::factory()->create();
    $user->assignRole('admin');
  
    $region = Region::factory()->create(['tax_provider_id' => $old_attr->id]);

    login($user);

    $response = $this->putJson(action([AdminRegionController::class,'update'], $region->uuid),[
        'tax_provider_id' => $new_attr->id
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Region::class, ['tax_provider_id' => $new_attr->id, 'id' => $region->id ]);
});

it('admin can update region countries', function(){

    TaxProvider::factory()->create(['id' => 1]);
    TaxProvider::factory()->create(['id' => 2]);

    $user = User::factory()->create();
    $user->assignRole('admin');
  
    $region = Region::factory()->create();

    $countries = Country::inRandomOrder()->limit(3)->get();
    $countries_ids = $countries->pluck('id');
    
    
    login($user);

    $response = $this->putJson(action([AdminRegionController::class,'updateCountries'], $region->uuid),[
        'countries' => $countries_ids
    ]);
    $response->assertOk();

    foreach($countries as $country){
        $this->assertDatabaseHas(Country::class, ['id' => $country->id, 'region_id' => $region->id]);
    }
});



it('admin can remove countries from region', function(){

    TaxProvider::factory()->create(['id' => 1]);
    TaxProvider::factory()->create(['id' => 2]);

    $user = User::factory()->create();
    $user->assignRole('admin');
  
    $region = Region::factory()->create();
    $country1 = Country::factory()->for($region)->create();
    $country2 = Country::factory()->for($region)->create();
    $country3 = Country::factory()->for($region)->create();

    $countries = collect([$country1,$country2]);
    $countries = $countries->map(function($item, $key){
        return $item->id;
    });
     
    login($user);

    $response = $this->deleteJson(action([AdminRegionController::class,'removeCountries'], $region->uuid),[
        'countries' => $countries
    ]);
    $response->assertOk();

    $this->assertDatabaseHas(Country::class, ['id' => $country1->id, 'region_id' => null]);
    $this->assertDatabaseHas(Country::class, ['id' => $country2->id, 'region_id' => null]);
    $this->assertDatabaseHas(Country::class, ['id' => $country3->id, 'region_id' => $region->id]);

});

it('can delete region', function(){
    TaxProvider::factory()->create();

    $user = User::factory()->create();
    $user->assignRole('admin');

    $region = Region::factory()->create();

    login($user);

    $response = $this->deleteJson(action([AdminRegionController::class,'update'], $region->uuid));

    $response->assertOk();

    $this->assertDatabaseMissing(Region::class, ['id'=> $region->id]);
});