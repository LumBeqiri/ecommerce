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
        Schema::create('variant_prices', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->integer('price');
            $table->foreignId('variant_id')->constrained('variants')->cascadeOnDelete();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->integer('min_quantity')->nullable();
            $table->integer('max_quantity')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['variant_id', 'region_id', 'deleted_at'], 'unique_variant_region_deleted_at');
        });
    }

};
