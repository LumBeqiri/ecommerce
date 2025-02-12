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
        Schema::create('discount_rules', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('description');
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            // instead of enum use string
            $table->string('discount_type');
            $table->string('operator')->default('in');
            $table->decimal('value', 10, 2);
            $table->string('allocation')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }
};
