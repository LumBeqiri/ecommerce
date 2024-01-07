<?php

namespace Database\Seeders;

use App\Models\User;
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

        $vendor = User::factory()->create([
            'uuid' => '0eaf6d30-9b51-11ed-a8fc-0242ac120002',
            'email' => 'drin@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        $vendor->assignRole('vendor');
        User::factory($usersQuantity)->create();
    }
}
