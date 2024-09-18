<?php

namespace Database\Seeders;

use App\Models\Buyer;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersQuantity = 20;

        $adminUser = User::factory()->create([
            'ulid' => '01J82QDMSMR602KT1KKE52101Y',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        $adminUser->assignRole('admin');

        $vendorUser = User::factory()->create([
            'ulid' => '01J82QDTQG96YR2VZT3MZM0E5S',
            'email' => 'vendor@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        Vendor::factory()->create([
            'user_id' => $vendorUser->id,
        ]);

        $vendorUser->assignRole('vendor');

        $buyerUser = User::factory()->create([
            'email' => 'buyer@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        Buyer::factory()->create([
            'user_id' => $buyerUser->id,
            'first_name' => 'Lejla',
            'last_name' => 'Buyer',
        ]);

        User::factory($usersQuantity)->create();
    }
}
