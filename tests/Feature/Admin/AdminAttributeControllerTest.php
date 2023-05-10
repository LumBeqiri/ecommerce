<?php

use App\Models\Attribute;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

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

    $user = User::factory()->create();

    $user->assignRole('admin');

    Product::factory()->create();

    login($user);

    // Create a product for testing
    $product = Product::factory()->create();

    // Define valid attribute data
    $attribute_type = 'size';
    $attribute_value = 'large';
    $attributeData = [
        'product_id' => $product->uuid,
        'attribute_type' => $attribute_type,
        'attribute_value' => $attribute_value,
    ];

    // Test attribute creation
    $response = $this->postJson(route('attributes.store'), $attributeData);

    $response->assertStatus(JsonResponse::HTTP_CREATED);

    $this->assertDatabaseHas(Attribute::class, ['product_id' => $product->id, 'attribute_type' => $attribute_type, 'attribute_value' => $attribute_value]);
});
