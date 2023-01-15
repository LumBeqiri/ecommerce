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
            $table->id();
            $table->uuid('uuid');
            $table->string('name');
            $table->string('product_short_description')->nullable();
            $table->text('product_long_description')->nullable();
            $table->foreignId('seller_id')->constrained('users');
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);
            $table->string('publish_status')->default(Product::DRAFT);
            $table->boolean('discountable');
            $table->foreignId('origin_country')->nullable()->constrained('countries');
            $table->string('thumbnail');

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
