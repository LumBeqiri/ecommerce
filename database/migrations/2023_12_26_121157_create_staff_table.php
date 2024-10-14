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
            $table->ulid('ulid');
            $table->foreignId('user_id')->constrained('users');
            $table->string('position');
            $table->string('status')->nullable();
            $table->string('notes', 500)->nullable();
            $table->string('address')->nullable();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->date('start_date')->nullable();
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
