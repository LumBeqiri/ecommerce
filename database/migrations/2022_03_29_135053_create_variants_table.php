<?php

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->references('id')->on('products');
            $table->string('sku');
            $table->string('variant_name');
            $table->string('short_description');
            $table->text('long_description');
            $table->integer('price');
            $table->integer('stock')->unsigned();
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);
            $table->integer('discount_id')->references('id')->on('discounts')->nullable();
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
        Schema::dropIfExists('variants');
    }
}
