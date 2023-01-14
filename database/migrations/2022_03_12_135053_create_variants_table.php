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
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('variant_name');
            $table->string('variant_short_description')->nullable();
            $table->text('variant_long_description')->nullable();
            $table->integer('stock')->unsigned();
            $table->boolean('manage_inventory');
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);
            $table->string('publish_status')->default(Product::DRAFT);
            $table->string('sku');
            $table->string('barcode')->nullable();
            $table->string('ean')->nullable();
            $table->string('upc')->nullable();
            $table->boolean('allow_backorder')->nullable();
            $table->string('material',255)->nullable();
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
