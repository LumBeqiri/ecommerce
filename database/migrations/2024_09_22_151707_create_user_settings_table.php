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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid');

            $table->string('phone')->nullable();
            $table->string('city')->nullable();

            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->string('zip')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('theme')->default('light');

            $table->timestamps();
            $table->softDeletes();
        });
    }
};
