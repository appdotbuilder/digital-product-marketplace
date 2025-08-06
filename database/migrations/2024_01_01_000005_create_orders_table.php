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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled', 'refunded', 'disputed'])->default('pending');
            $table->enum('escrow_status', ['none', 'held', 'released', 'refunded'])->default('none');
            $table->datetime('escrow_release_at')->nullable()->comment('When escrow will be automatically released');
            $table->text('buyer_notes')->nullable();
            $table->text('seller_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->json('product_data')->comment('Snapshot of product data at time of purchase');
            $table->json('delivery_data')->nullable()->comment('Account details or download links');
            $table->datetime('delivered_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('buyer_id');
            $table->index('seller_id');
            $table->index('product_id');
            $table->index('status');
            $table->index('escrow_status');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};