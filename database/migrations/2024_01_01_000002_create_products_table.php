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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('images')->nullable()->comment('Array of image paths');
            $table->decimal('price', 10, 2);
            $table->enum('type', ['downloadable', 'account'])->comment('Product type');
            $table->string('download_file')->nullable()->comment('File path for downloadable products');
            $table->text('account_details')->nullable()->comment('Account credentials or details');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('stock_quantity')->default(1)->comment('Available quantity');
            $table->integer('sold_count')->default(0)->comment('Number of times sold');
            $table->decimal('rating', 3, 2)->default(0)->comment('Average rating');
            $table->integer('review_count')->default(0);
            $table->json('tags')->nullable()->comment('Product tags');
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
            $table->index('category_id');
            $table->index('slug');
            $table->index('type');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};