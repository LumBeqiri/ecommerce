<?php

use App\Http\Controllers\Admin\Attributes\AdminAttributeController;
use App\Models\Attribute;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Vendor;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
    Currency::factory()->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $this->seed(RoleAndPermissionSeeder::class);
});

it('can store attribute', function () {

    $user = User::factory()->create();

    $user->assignRole('admin');

    Vendor::factory()->create([
        'country_id' => Country::first()->id,
        'user_id' => $user->id,
    ]);

    Product::factory()->create();

    login($user);

    // Define valid attribute data
    $attribute_type = 'size';
    $attribute_value = 'large';
    $attributeData = [
        'attribute_type' => $attribute_type,
        'attribute_value' => $attribute_value,
    ];

    $response = $this->postJson(action([AdminAttributeController::class, 'store']), $attributeData);

    $response->assertStatus(JsonResponse::HTTP_CREATED);

    $this->assertDatabaseHas(Attribute::class, ['attribute_type' => $attribute_type, 'attribute_value' => $attribute_value]);
});
