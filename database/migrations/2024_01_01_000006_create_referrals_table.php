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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referee_id')->constrained('users')->cascadeOnDelete();
            $table->string('referral_code')->unique();
            $table->decimal('commission_rate', 5, 2)->default(10.00)->comment('Commission percentage');
            $table->decimal('total_earned', 15, 2)->default(0)->comment('Total commissions earned');
            $table->boolean('is_active')->default(true);
            $table->datetime('first_purchase_at')->nullable()->comment('When referee made first purchase');
            $table->timestamps();
            
            $table->index('referrer_id');
            $table->index('referee_id');
            $table->index('referral_code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};