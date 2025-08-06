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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->unique();
            $table->enum('type', ['deposit', 'withdrawal', 'purchase', 'sale', 'referral_bonus', 'escrow_hold', 'escrow_release']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_after', 15, 2)->comment('Wallet balance after this transaction');
            $table->text('description');
            $table->json('metadata')->nullable()->comment('Additional transaction data');
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            $table->nullableMorphs('transactionable');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('transaction_id');
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};