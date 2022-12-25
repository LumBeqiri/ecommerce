<?php

use App\Http\Controllers\Admin\Variant\AdminVariantController;
use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use Database\Seeders\RoleAndPermissionSeeder;


beforeEach(function(){
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);

});


it('admin can update variant name', function(){
    User::factory()->count(10)->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create();
   
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updatedName = 'new name';
    login($user);
    
    $response = $this->putJson(action([AdminVariantController::class, 'update'],$variant->uuid),[
        'variant_name' => $updatedName
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_name' => $updatedName]);

});

it('admin can update variant sku', function(){
    User::factory()->count(10)->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create();
   
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updated = 'new sku';
    login($user);
    
    $response = $this->putJson(action([AdminVariantController::class, 'update'],$variant->uuid),[
        'sku' => $updated
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['sku' => $updated]);

});


it('admin can update variant short description', function(){
    User::factory()->count(10)->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create();
   
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updated = 'new sku';
    login($user);
    
    $response = $this->putJson(action([AdminVariantController::class, 'update'],$variant->uuid),[
        'short_description' => $updated
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['short_description' => $updated]);

});


it('admin can update variant long description', function(){
    User::factory()->count(10)->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create();
   
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updated = 'new sku';
    login($user);
    
    $response = $this->putJson(action([AdminVariantController::class, 'update'],$variant->uuid),[
        'long_description' => $updated
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['long_description' => $updated]);

});

it('admin can update variant price', function(){
    User::factory()->count(10)->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create();
   
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updated = 230;
    login($user);
    
    $response = $this->putJson(action([AdminVariantController::class, 'update'],$variant->uuid),[
        'price' => $updated
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['price' => $updated]);

});

it('admin can not update variant with negative price', function(){
    User::factory()->count(10)->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create();
   
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updated = -230;
    login($user);
    
    $response = $this->putJson(action([AdminVariantController::class, 'update'],$variant->uuid),[
        'price' => $updated
    ]);

    $response->assertStatus(422);

});


it('admin can update variant stock', function(){
    User::factory()->count(10)->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create(['stock' => 5]);
   
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updated = 23;
    login($user);
    
    $response = $this->putJson(action([AdminVariantController::class, 'update'],$variant->uuid),[
        'stock' => $updated
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['stock' => $updated]);

});

it('admin can not update variant with negative stock value', function(){
    User::factory()->count(10)->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create(['stock' => 5]);
   
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updated = -23;
    login($user);
    
    $response = $this->putJson(action([AdminVariantController::class, 'update'],$variant->uuid),[
        'stock' => $updated
    ]);

    $response->assertStatus(422);

});

it('admin can delete variant', function(){

    User::factory()->count(10)->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create(['stock' => 5]);
   
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminVariantController::class, 'update'], $variant->uuid));

    $response->assertOk();

    $this->assertDatabaseMissing(Variant::class, ['id' => $variant->id]);

});