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
        Schema::create('wallet_transfer_logs', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('from_wallet_id')->references('id')->on('wallets')->cascadeOnDelete()->nullabel(false);
            $table->foreignId('to_wallet_id')->references('id')->on('wallets')->cascadeOnDelete()->nullabel(false);
            $table->string('description')->nullable(false)->default('text');
            $table->integer('amount')->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transfer_logs');
    }
};
