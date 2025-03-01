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
        Schema::create('payment_processors', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('name');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->timestamps();
        });
    }
};
