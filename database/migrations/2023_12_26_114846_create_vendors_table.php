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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid');
            $table->string('vendor_name');
            $table->string('city');
            $table->foreignId('country_id')->constrained();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('status')->default(0);
            $table->date('approval_date')->nullable();
            $table->string('website')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
