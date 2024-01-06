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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropColumn(['name','city', 'country_id', 'zip', 'shipping_address', 'phone']);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('city')->nullable();
            $table->foreignId('country_id')->constrained();
            $table->integer('zip')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('phone')->nullable();
        });
    }
};
