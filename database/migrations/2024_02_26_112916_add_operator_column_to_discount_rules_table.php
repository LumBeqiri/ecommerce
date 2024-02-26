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
        Schema::table('discount_rules', function (Blueprint $table) {
            $table->enum('operator', ['in', 'not_in'])->default('in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discount_rules', function (Blueprint $table) {
            $table->dropColumn('operator');
        });
    }
};
