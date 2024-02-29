<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('variant_prices', function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
        });
    }
};