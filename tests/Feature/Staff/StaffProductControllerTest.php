<?php

use App\Http\Controllers\Staff\StaffProductController;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\Staff;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Vendor;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
});

test('staff can update product name', function () {
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create();

    Staff::factory()->create(['user_id' => $user->id, 'vendor_id' => $vendor->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $user->givePermissionTo('update-products');
    $user->assignRole('manager');
    $updatedName = 'new name';
    login($user);

    $response = $this->putJson(action([StaffProductController::class, 'update'], $product->ulid), [
        'product_name' => $updatedName,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['product_name' => $updatedName]);
});

it('staff can not update product name of another vendor', function () {
    $oldName = 'old-name';
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create();
    $vendor2 = Vendor::factory()->create();

    Staff::factory()->create(['user_id' => $user->id, 'vendor_id' => $vendor->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor2->id, 'product_name' => $oldName]);

    $user->givePermissionTo('update-products');
    $user->assignRole('manager');
    $updatedName = 'new name';
    login($user);

    $response = $this->putJson(action([StaffProductController::class, 'update'], $product->ulid), [
        'product_name' => $updatedName,
    ]);

    $response->assertForbidden();

    $this->assertDatabaseHas(Product::class, ['product_name' => $oldName]);
});

it('staff can delete product', function () {

    $staffUser = User::factory()->create();
    $vendorUser = User::factory()->create();

    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('delete-products');

    login($staffUser);

    $response = $this->deleteJson(action([StaffProductController::class, 'destroy'], $product->ulid));

    $response->assertOk();

    $this->assertSoftDeleted(Product::class, ['id' => $product->id]);
});

it('staff can not  delete product of another vendor', function () {

    $staffUser = User::factory()->create();
    $vendorUser = User::factory()->create();
    $anotherVendorUser = User::factory()->create();

    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    $anotherVendor = Vendor::factory()->create(['user_id' => $anotherVendorUser->id]);
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);
    $product = Product::factory()->create(['vendor_id' => $anotherVendor->id]);

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('delete-products');

    login($staffUser);

    $response = $this->deleteJson(action([StaffProductController::class, 'destroy'], $product->ulid));

    $response->assertForbidden();

    $this->assertDatabaseHas(Product::class, ['id' => $product->id, 'deleted_at' => null]);
});
