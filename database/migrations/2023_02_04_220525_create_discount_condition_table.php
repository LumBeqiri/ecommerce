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
        Schema::create('discount_condition', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('model_type');
            $table->enum('operator',['in', 'not_in']);
            $table->foreignId('discount_rule_id')->constrained('discount_rules')->cascadeOnDelete();
            $table->json('metadata');
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
        Schema::dropIfExists('discount_condition');
    }
};
