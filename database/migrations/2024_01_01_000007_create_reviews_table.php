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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->integer('rating')->comment('1-5 star rating');
            $table->text('comment')->nullable();
            $table->boolean('is_verified')->default(true)->comment('Review from verified purchase');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            
            $table->unique(['user_id', 'product_id', 'order_id']);
            $table->index('product_id');
            $table->index('rating');
            $table->index(['is_published', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};