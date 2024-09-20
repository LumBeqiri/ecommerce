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
            $table->ulid('ulid');
            $table->string('product_name');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);
            $table->string('publish_status')->default(Product::DRAFT);
            $table->boolean('discountable')->default(0);
            $table->foreignId('origin_country')->nullable()->constrained('countries');
            $table->string('thumbnail')->nullable();
            $table->foreignId('discount_id')->nullable()->constrained('discounts');
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
