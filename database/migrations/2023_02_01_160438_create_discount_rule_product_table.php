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
        Schema::create('discount_rule_product', function (Blueprint $table) {
            $table->foreignId('discount_rule_id')->constrained('discount_rules')->deleteOnCascade();
            $table->foreignId('product_id')->constrained('products')->deleteOnCascade();
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
        Schema::dropIfExists('discount_rule_product');
    }
};
