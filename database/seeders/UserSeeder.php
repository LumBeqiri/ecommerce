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
        $usersQuantity = 50;

        $adminUser = User::factory()->create([
            'uuid' => 'f4e367e1-aefe-33de-8e38-5f8b2ef1bead',
            'email' => 'lum@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        $adminUser->assignRole('admin');

        $vendorUser = User::factory()->create([
            'uuid' => '0eaf6d30-9b51-11ed-a8fc-0242ac120002',
            'email' => 'drin@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        Vendor::factory()->create([
            'user_id' => $vendorUser->id,
        ]);

        $vendorUser->assignRole('vendor');

        $buyerUser = User::factory()->create([
            'email' => 'lejla@gmail.com',
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
