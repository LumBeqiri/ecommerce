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
            $table->ulid('ulid');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('variant_name');
            $table->string('variant_short_description', 255)->nullable();
            $table->string('variant_long_description', 1000)->nullable();
            $table->integer('stock')->unsigned()->default(0);
            $table->boolean('manage_inventory')->nullable();
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);
            $table->string('publish_status')->default(Product::DRAFT);
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('ean')->nullable();
            $table->string('upc')->nullable();
            $table->boolean('allow_backorder')->nullable();
            $table->string('material', 255)->nullable();

            $table->integer('weight')->nullable();
            $table->string('weight_unit')->nullable();

            $table->integer('length')->nullable();
            $table->string('length_unit')->nullable();

            $table->integer('height')->nullable();
            $table->string('height_unit')->nullable();

            $table->integer('width')->nullable();
            $table->string('width_unit')->nullable();
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
        Schema::dropIfExists('variants');
    }
}
