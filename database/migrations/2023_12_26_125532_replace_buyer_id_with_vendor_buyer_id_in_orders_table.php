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
        // Drop existing foreign key constraint
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
        });

        // Drop the buyer_id column
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('buyer_id');
        });

        // Add the new foreign key constraint and column
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('buyer_id')->constrained('buyers');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('buyer_id')->constrained('users');

            $table->dropForeign(['buyer_id']);
            $table->dropColumn('buyer_id');
        });
    }
};
