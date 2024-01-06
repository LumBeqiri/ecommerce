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

        Schema::table('products', function (Blueprint $table) {
            // Check if the user_id column exists before dropping foreign key
            if (Schema::hasColumn('products', 'user_id')) {
                // Drop foreign key constraint
                $table->dropForeign(['user_id']);
                // Drop the user_id column
                $table->dropColumn('user_id');
            }
        
            // Add vendor_id column and foreign key
            $table->foreignId('vendor_id')->constrained('vendors');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');

            $table->dropForeign(['vendor_id']);
            $table->dropColumn('vendor_id');
        });
    }
};
