<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('variant_name');
            $table->string('variant_short_description',255)->nullable();
            $table->string('variant_long_description',1000)->nullable();
            $table->integer('stock')->unsigned();
            $table->boolean('manage_inventory');
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);
            $table->string('publish_status')->default(Product::DRAFT);
            $table->string('sku');
            $table->string('barcode')->nullable();
            $table->string('ean')->nullable();
            $table->string('upc')->nullable();
            $table->boolean('allow_backorder')->nullable();
            $table->string('material', 255)->nullable();
            $table->integer('weight')->nullable();
            $table->integer('length')->nullable();
            $table->integer('height')->nullable();
            $table->integer('width')->nullable();

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
