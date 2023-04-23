<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('regions')->truncate();
        Schema::enableForeignKeyConstraints();

        $region = [
            'uuid' => '108860ae-f8d1-4d94-83d8-0e7aa30a38ef',
            'title' => 'EU',
            'currency_id' => 1,
            'tax_rate' => 22,
            'tax_code' => 'IJK012',
        ];

        $region2 = [
            'uuid' => '40cf8578-a8e8-4949-8f42-fe053e442937',
            'title' => 'ASIA',
            'currency_id' => 2,
            'tax_rate' => 14,
            'tax_code' => 'ZCAC7A3',
        ];

        Region::create($region);
        Region::create($region2);
    }
}
