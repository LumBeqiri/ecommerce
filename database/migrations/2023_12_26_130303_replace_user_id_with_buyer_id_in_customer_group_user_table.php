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
        Schema::table('customer_group_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->foreignId('buyer_id')->constrained('buyers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_group_user', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
            $table->dropColumn('buyer_id');

            $table->foreignId('user_id')->constrained('users');
        });
    }
};
