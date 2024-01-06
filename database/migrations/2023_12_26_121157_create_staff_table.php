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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('user_id')->constrained('users');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position');
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('status')->nullable();
            $table->string('notes', 500)->nullable();
            $table->string('address')->nullable();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('country_id')->constrained('countries');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
