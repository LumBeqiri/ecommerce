<?php

namespace Database\Seeders;

use App\Models\TaxProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TaxProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('tax_providers')->truncate();
        Schema::enableForeignKeyConstraints();
        $tax_provider = [
            'ulid' => Str::ulid(),
            'tax_provider' => 'default',
            'is_installed' => 1,
        ];

        TaxProvider::create($tax_provider);
    }
}
