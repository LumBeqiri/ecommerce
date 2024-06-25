<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentProcessor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentProcessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentProcessor::factory()->count(2)->create();
    }
}
