<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->uuid('uuid');
            $table->string('product_name');
            $table->string('product_short_description',255)->nullable();
            $table->string('product_long_description',1000)->nullable();
            $table->foreignId('seller_id')->constrained('users');
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);
            $table->string('publish_status')->default(Product::DRAFT);
            $table->boolean('discountable')->default(0);
            $table->foreignId('origin_country')->nullable()->constrained('countries');
            $table->string('thumbnail')->nullable();

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
        Schema::dropIfExists('products');
    }
}
