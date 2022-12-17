<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attribute::create([
            'attribute_type' => 'size',
            'attribute_value' => 's'
        ]);

        Attribute::create([
            'attribute_type' => 'size',
            'attribute_value' => 'm'
        ]);

        Attribute::create([
            'attribute_type' => 'size',
            'attribute_value' => 'l'
        ]);

        Attribute::create([
            'attribute_type' => 'color',
            'attribute_value' => 'red'
        ]);

        Attribute::create([
            'attribute_type' => 'color',
            'attribute_value' => 'green'
        ]);

        Attribute::create([
            'attribute_type' => 'color',
            'attribute_value' => 'blue'
        ]);
    }
}