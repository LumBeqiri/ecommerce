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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('city')->nullable();
            $table->foreignId('country_id')->constrained();
            $table->integer('zip')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();

            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
