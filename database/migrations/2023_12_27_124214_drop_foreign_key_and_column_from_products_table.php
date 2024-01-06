<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['seller_id']);

            // Drop the 'selled_id' column
            $table->dropColumn('seller_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Recreate the 'selled_id' column
            $table->unsignedBigInteger('seller_id')->nullable();

            // Recreate the foreign key constraint
            $table->foreign('seller_id')->references('id')->on('other_table')->onDelete('set null');
        });
    }
};
