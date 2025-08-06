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
        Schema::create('crypto_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_hash')->unique();
            $table->string('cryptocurrency')->comment('BTC, ETH, USDT, etc.');
            $table->decimal('crypto_amount', 20, 8)->comment('Amount in cryptocurrency');
            $table->decimal('usd_amount', 15, 2)->comment('USD equivalent at time of deposit');
            $table->decimal('exchange_rate', 20, 8)->comment('Crypto to USD rate');
            $table->string('wallet_address')->comment('Deposit address');
            $table->integer('confirmations')->default(0);
            $table->integer('required_confirmations')->default(3);
            $table->enum('status', ['pending', 'confirmed', 'failed', 'expired'])->default('pending');
            $table->datetime('expires_at')->comment('When deposit request expires');
            $table->json('raw_transaction_data')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('transaction_hash');
            $table->index('status');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_deposits');
    }
};