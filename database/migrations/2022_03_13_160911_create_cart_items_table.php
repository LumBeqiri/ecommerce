<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid');
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('variant_id')->constrained('variants')->cascadeOnDelete();
            $table->integer('quantity');
            $table->foreignId('variant_price_id')->constrained('variant_prices');

            $table->integer('price');

            $table->integer('discounted_price')->nullable();

            $table->foreignId('currency_id')->nullable()->constrained();
            $table->timestamps();
        });
    }
}
