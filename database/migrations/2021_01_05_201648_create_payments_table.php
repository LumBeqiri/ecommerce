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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->integer('amount');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->foreignId('payment_processor_id')->constrained('payment_processors');
            $table->timestamps();
        });
    }
};
