<?php

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('sku');
            $table->double('price',10,2);
            $table->string('weight');
            $table->string('size');
            $table->string('short_desc');
            $table->string('long_desc');
            $table->string('image_1');
            $table->string('image_2');
            $table->integer('seller_id')->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->integer('stock')->unsigned();
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);

            $table->foreign('seller_id')->references('id')->on('users');
            $table->foreign('currency_id')->references('id')->on('currencies');


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
        Schema::dropIfExists('products');
    }
}
