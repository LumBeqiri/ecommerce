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
            'ulid' => '01J82QCCQQ08YH3AZJEP0B7NRS',
            'title' => 'EU',
            'currency_id' => 1,
            'tax_rate' => 22,
            'tax_code' => 'IJK012',
        ];

        $region2 = [
            'ulid' => '01J82QCR6HBPM42MXMWK4B824G',
            'title' => 'ASIA',
            'currency_id' => 2,
            'tax_rate' => 14,
            'tax_code' => 'ZCAC7A3',
        ];

        Region::create($region);
        Region::create($region2);
    }
}
