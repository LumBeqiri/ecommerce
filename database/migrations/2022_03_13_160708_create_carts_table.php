<?php

use App\Models\Region;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid');
            $table->foreignId('buyer_id')->constrained('buyers');
            $table->integer('total_cart_price')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->boolean('has_been_discounted')->default(false);
            $table->foreignId('payment_id')->nullable()->constrained('payments');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors');
            $table->foreignIdFor(Region::class, 'region_id')->constrained();
            $table->timestamps();
        });
    }

}
