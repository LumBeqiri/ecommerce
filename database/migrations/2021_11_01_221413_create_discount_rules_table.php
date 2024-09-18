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
            $table->enum('discount_type', ['fixed_amount', 'percentage', 'free_shipping']);
            $table->double('value', 8, 2);
            $table->enum('allocation', ['total_amount', 'item_specific'])->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_rules');
    }
};
