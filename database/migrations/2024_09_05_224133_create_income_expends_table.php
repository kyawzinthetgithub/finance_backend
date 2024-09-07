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
        Schema::create('income_expends', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('category_id')->references('id')->on('wallet_types')->cascadeOnDelete()->nullabel(false);
            $table->foreignId('wallet_id')->references('id')->on('wallet_types')->cascadeOnDelete()->nullabel(false);
            $table->string('description')->nullable(false);
            $table->integer('amount')->nullable(false);
            $table->enum('type',['income','expend'])->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_expends');
    }
};
