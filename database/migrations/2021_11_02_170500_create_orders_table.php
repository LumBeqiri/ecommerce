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
            $table->uuid('uuid');
            $table->foreignId('buyer_id')->constrained('users');
            $table->string('ship_name');
            $table->string('ship_address');
            $table->string('ship_city');
            $table->string('ship_state');
            $table->float('order_tax');
            $table->float('total');
            $table->dateTime('order_date');
            $table->string('order_shipped');
            $table->string('order_email');
            $table->string('order_phone');
            $table->integer('payment_id');
           

            $table->timestamps();
            $table->softDeletes();
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
