<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);

        Permission::create(['name' => 'create-products']);
        Permission::create(['name' => 'edit-products']);
        Permission::create(['name' => 'delete-products']);

        
        Permission::create(['name' => 'create-categories']);
        Permission::create(['name' => 'edit-categories']);
        Permission::create(['name' => 'delete-categories']);

        Permission::create(['name' => 'create-attributes']);
        Permission::create(['name' => 'edit-attributes']);
        Permission::create(['name' => 'delete-attributes']);

        Permission::create(['name' => 'create-roles']);
        Permission::create(['name' => 'edit-roles']);
        Permission::create(['name' => 'delete-roles']);

        Permission::create(['name' => 'create-carts']);
        Permission::create(['name' => 'edit-carts']);
        Permission::create(['name' => 'delete-carts']);

        
        Permission::create(['name' => 'create-discounts']);
        Permission::create(['name' => 'edit-discounts']);
        Permission::create(['name' => 'delete-discounts']);

        Permission::create(['name' => 'create-orders']);
        Permission::create(['name' => 'edit-orders']);
        Permission::create(['name' => 'delete-orders']);


        $adminRole = Role::create(['name' => 'admin']);

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
