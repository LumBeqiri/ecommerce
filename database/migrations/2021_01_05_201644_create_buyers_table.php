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
            $table->ulid('ulid');
            $table->string('shipping_address')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();

            $table->softDeletes();

        });
    }
};
