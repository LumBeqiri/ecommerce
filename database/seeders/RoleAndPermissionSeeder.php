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

        Permission::create(['name' => 'create-discounts', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-discounts', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-discounts', 'guard_name' => 'api']);

        Permission::create(['name' => 'create-orders', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit-orders', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete-orders', 'guard_name' => 'api']);

        $adminRole = Role::create(['name' => 'admin']);
        Role::create(['name' => 'vendor']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'employee']);

        $adminRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users',

            'create-products',
            'edit-products',
            'delete-products',

            'create-categories',
            'edit-categories',
            'delete-categories',

            'create-attributes',
            'edit-attributes',
            'delete-attributes',

            'create-carts',
            'edit-carts',
            'delete-carts',

            'create-discounts',
            'edit-discounts',
            'delete-discounts',

            'create-orders',
            'edit-orders',
            'delete-orders',

            'create-roles',
            'edit-roles',
            'delete-roles',
        ]);
    }
}
