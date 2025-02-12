<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('name', 255);
            $table->json('metadata')->nullable();
            $table->foreignId('buyer_id')->constrained('buyers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

};
