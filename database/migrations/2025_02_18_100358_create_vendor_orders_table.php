<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('order_id')->constrained('orders');

            // Tax information:
            // - tax_rate: percentage as a decimal (e.g., 7.50 for 7.5%)
            // - tax_total: total tax in cents
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->unsignedBigInteger('tax_total')->nullable();

            // Order total in cents
            $table->unsignedBigInteger('total');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }
};
