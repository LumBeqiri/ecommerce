<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            Vendor::create([
                'uuid' => $faker->uuid,
                'vendor_name' => $faker->company,
                'city' => $faker->city,
                'country_id' => rand(1, 10), // Assuming you have countries with IDs from 1 to 10
                'user_id' => rand(1, 10), // Assuming you have countries with IDs from 1 to 10
                'status' => $faker->boolean,
                'approval_date' => $faker->date,
                'website' => $faker->url,
            ]);
        }
    }
}
