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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('category_id')->nullable()->references('id')->on('categories')->onDelete('cascade')->constrained();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade')->constrained();
            $table->integer('total');
            $table->integer('spend_amount')->nullable();
            $table->integer('remaining_amount')->nullable();
            $table->boolean('alert')->default(0)->nullable();
            $table->integer('usage')->default(0);
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
