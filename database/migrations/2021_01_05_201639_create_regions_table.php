<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('title');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->integer('tax_rate')->nullable();
            $table->string('tax_code')->nullable();
            $table->foreignId('tax_provider_id')->nullable()->constrained('tax_providers');
            $table->timestamps();
        });
    }
};
