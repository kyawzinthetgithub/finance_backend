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
            $table->foreignId('category_id')->references('id')->on('categories')->cascadeOnDelete()->constrained();
            $table->foreignId('wallet_id')->references('id')->on('wallets')->cascadeOnDelete()->constrained();
            $table->string('description');
            $table->integer('amount');
            $table->enum('type',['income','expend']);
            $table->timestamp('action_date')->nullable();
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
