<?php

namespace Database\Seeders;

use App\Models\PaymentProcessor;
use Illuminate\Database\Seeder;

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
