<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid');
            $table->foreignId('buyer_id')->constrained('buyers');

            // Shipping information
            $table->string('shipping_name')->nullable();
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->foreignId('shipping_country_id')->constrained('countries');

            // Tax information:
            // - tax_rate: percentage as a decimal (e.g., 7.50 for 7.5%)
            // - tax_total: total tax in cents
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->unsignedBigInteger('tax_total')->nullable();

            // Order total in cents
            $table->unsignedBigInteger('total');
            $table->foreignId('currency_id')->constrained('currencies');

            // Order date: we record when the order was placed.
            $table->dateTime('ordered_at')->useCurrent();

            // Shipping status: we record when the order was shipped.
            $table->dateTime('shipped_at')->nullable();

            $table->string('order_email');
            $table->string('order_phone');

            $table->foreignId('payment_id')->nullable()->constrained('payments');
            $table->timestamps();
        });
    }
}
