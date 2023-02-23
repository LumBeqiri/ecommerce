<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            'name' => 'Lum Beqiri',
            'email' => 'lum@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        $adminUser->assignRole('admin');

        User::factory()->create([
            'uuid' => '0eaf6d30-9b51-11ed-a8fc-0242ac120002',
            'name' => 'Drin Beqiri',
            'email' => 'drin@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        User::factory($usersQuantity)->create();
    }
}
