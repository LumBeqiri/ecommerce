<?php

use App\Models\User;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Attribute;
use App\Models\TaxProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
    Currency::factory()->count(5)->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $this->seed(RoleAndPermissionSeeder::class);
});

it('can store attribute', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Vendor::factory()->create();

    $user = User::factory()->create();

    $user->assignRole('admin');

    Product::factory()->create();
    
    login($user);

    // Define valid attribute data
    $attribute_type = 'size';
    $attribute_value = 'large';
    $attributeData = [
        'attribute_type' => $attribute_type,
        'attribute_value' => $attribute_value,
    ];

    $response = $this->postJson(route('attributes.store'), $attributeData);

    $response->assertStatus(JsonResponse::HTTP_CREATED);

    $this->assertDatabaseHas(Attribute::class, ['attribute_type' => $attribute_type, 'attribute_value' => $attribute_value]);
});
