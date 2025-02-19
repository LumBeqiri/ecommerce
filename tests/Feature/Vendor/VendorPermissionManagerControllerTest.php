<?php

use App\Models\User;
use App\Models\Staff;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\TaxProvider;
use Illuminate\Support\Facades\Bus;
use Database\Seeders\CurrencySeeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\User\Vendor\VendorPermissionManagerController;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
});

it('vendor can create a permission for user', function () {

    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);

    $staffUser = User::factory()->create();

    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);

    $vendorUser->assignRole('vendor');

    login($vendorUser);

    $permissionIds = Permission::take(2)->pluck('id')->toArray();

    $response = $this->putJson(action([VendorPermissionManagerController::class, 'update'], $staffUser->ulid), [
        'permissions' => $permissionIds,
    ]);

    $response->assertOk();

});

it('vendor can not create a permission for user not part of vendor', function () {

    $vendorUser = User::factory()->create();
    Vendor::factory()->create(['user_id' => $vendorUser->id]);

    $vendorUser2 = User::factory()->create();
    $vendor2 = Vendor::factory()->create(['user_id' => $vendorUser2->id]);

    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor2->id]);

    $vendorUser->assignRole('vendor');

    login($vendorUser);

    $response = $this->putJson(action([VendorPermissionManagerController::class, 'update'], $staffUser->ulid), [
        'permissions' => [1, 2],
    ]);

    $response->assertStatus(422);

});

it('vendor can delete a permission for user', function () {
    // Create a vendor user
    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);

    // Create a staff user associated with the vendor
    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);

    // Assign a role to the vendor user
    $vendorUser->assignRole('vendor');

    // Log in as the vendor user
    login($vendorUser);
    $permission_id = Permission::where('name', 'create-users')->first()->id;

    $staffUser->givePermissionTo(['create-users', 'edit-users']);

    $response = $this->deleteJson(action([VendorPermissionManagerController::class, 'destroy'], [$staffUser->ulid, 'permission_id' => $permission_id]));

    $response->assertOk();

    $this->assertDatabaseMissing('model_has_permissions', ['model_id' => $staffUser->id, 'permission_id' => $permission_id]);
});
