<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid');
            $table->foreignId('buyer_id')->constrained('buyers');
            $table->string('shipping_name')->nullable();
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_country');
            $table->float('order_tax')->nullable();
            $table->float('total');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->dateTime('order_date');
            $table->string('order_shipped')->default(false);
            $table->string('order_email');
            $table->string('order_phone');
            $table->foreignId('payment_id')->nullable()->constrained('payments');
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
        Schema::dropIfExists('orders');
    }
}
