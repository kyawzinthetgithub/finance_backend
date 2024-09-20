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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->constrained();
            $table->foreignId('wallet_type_id')->references('id')->on('wallet_types')->onDelete('cascade')->constrained();
            $table->foreignId('image_id')->nullable()->references('id')->on('images')->onDelete('cascade')->constrained();
            $table->string('name');
            $table->string('bank_name')->nullable();
            $table->integer('amount')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
