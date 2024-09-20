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
        Schema::create('customer_group_user', function (Blueprint $table) {
            $table->foreignId('buyer_id')->constrained('buyers')->cascadeOnDelete();
            $table->foreignId('customer_group_id')->constrained('customer_groups')->deleteOnCascade();
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
        Schema::dropIfExists('customer_group_user');
    }
};
