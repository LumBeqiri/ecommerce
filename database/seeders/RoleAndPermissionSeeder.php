<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create-users', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-users', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-users', 'guard_name' => 'api']);

        Permission::create(['name' => 'create-products', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-products', 'guard_name' => 'api']);
        Permission::create(['name' => 'update-products', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-products', 'guard_name' => 'api']);

        Permission::create(['name' => 'view-discounts', 'guard_name' => 'api']);
        Permission::create(['name' => 'create-discounts', 'guard_name' => 'api']);
        Permission::create(['name' => 'update-discounts', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-discounts', 'guard_name' => 'api']);

        Permission::create(['name' => 'create-variants', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-variants', 'guard_name' => 'api']);
        Permission::create(['name' => 'update-variants', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-variants', 'guard_name' => 'api']);

        Permission::create(['name' => 'create-categories', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-categories', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-categories', 'guard_name' => 'api']);

        Permission::create(['name' => 'create-attributes', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-attributes', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-attributes', 'guard_name' => 'api']);

        Permission::create(['name' => 'create-roles', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-roles', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-roles', 'guard_name' => 'api']);

        Permission::create(['name' => 'create-carts', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-carts', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-carts', 'guard_name' => 'api']);


        Permission::create(['name' => 'create-orders', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-orders', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-orders', 'guard_name' => 'api']);

        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'api']);

        $vendorRole = Role::create(['name' => 'vendor', 'guard_name' => 'api']);
        $vendorRole->givePermissionTo('create-discounts');
        $vendorRole->givePermissionTo('view-discounts');
        $vendorRole->givePermissionTo('update-discounts');
        $vendorRole->givePermissionTo('delete-discounts');
        Role::create(['name' => 'manager', 'guard_name' => 'api']);
        Role::create(['name' => 'staff', 'guard_name' => 'api']);
        Role::create(['name' => 'buyer', 'guard_name' => 'api']);

        Permission::create(['name' => 'manage-users', 'guard_name' => 'api']);
        Permission::create(['name' => 'manage-products', 'guard_name' => 'api']);
        Permission::create(['name' => 'manage-categories', 'guard_name' => 'api']);
        Permission::create(['name' => 'manage-attributes', 'guard_name' => 'api']);
        Permission::create(['name' => 'manage-carts', 'guard_name' => 'api']);
        Permission::create(['name' => 'manage-orders', 'guard_name' => 'api']);
        Permission::create(['name' => 'manage-discounts', 'guard_name' => 'api']);
        Permission::create(['name' => 'manage-roles', 'guard_name' => 'api']);

        $adminRole->givePermissionTo([
            'manage-users',

            'manage-products',

            'manage-categories',

            'manage-attributes',

            'manage-carts',

            'manage-discounts',

            'manage-orders',

            'manage-roles',
        ]);
    }
}
