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
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('balance', 15, 2)->default(0)->comment('Current wallet balance');
            $table->decimal('pending_balance', 15, 2)->default(0)->comment('Escrow balance');
            $table->decimal('total_earned', 15, 2)->default(0)->comment('Total earnings from sales');
            $table->decimal('total_spent', 15, 2)->default(0)->comment('Total spent on purchases');
            $table->timestamps();
            
            $table->unique('user_id');
            $table->index('balance');
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