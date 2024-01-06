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
            // Add the 'deleted_at' column for Soft Deletes
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Remove the 'deleted_at' column if rolling back the migration
            $table->dropSoftDeletes();
        });
    }
};
