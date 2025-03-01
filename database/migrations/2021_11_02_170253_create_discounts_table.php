<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors');
            $table->string('code');
            $table->boolean('is_dynamic')->nullable();
            $table->boolean('is_disabled')->default(false)->nullable();
            $table->foreignId('discount_rule_id')->constrained('discount_rules');
            $table->foreignId('parent_id')->nullable()->constrained('discounts');
            $table->timestamp('starts_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('ends_at')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_count')->nullable();

            $table->timestamps();
        });
    }
}
