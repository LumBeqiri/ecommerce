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
        Schema::create('order_variant', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('variant_id')->constrained('variants');

            $table->timestamps();
        });
    }


};
