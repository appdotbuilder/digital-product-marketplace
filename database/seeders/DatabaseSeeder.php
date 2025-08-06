<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CryptoDeposit;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users with different roles
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@digitalhub.com',
            'role' => 'admin',
            'referral_code' => Str::upper(Str::random(8)),
            'is_verified_seller' => true,
        ]);

        $seller1 = User::factory()->create([
            'name' => 'John Seller',
            'email' => 'seller@digitalhub.com',
            'role' => 'seller',
            'referral_code' => Str::upper(Str::random(8)),
            'is_verified_seller' => true,
        ]);

        $seller2 = User::factory()->create([
            'name' => 'Jane Merchant',
            'email' => 'merchant@digitalhub.com',
            'role' => 'seller',
            'referral_code' => Str::upper(Str::random(8)),
            'is_verified_seller' => true,
        ]);

        $buyer = User::factory()->create([
            'name' => 'Test Buyer',
            'email' => 'buyer@digitalhub.com',
            'role' => 'buyer',
            'referral_code' => Str::upper(Str::random(8)),
        ]);

        // Create additional random users
        $users = User::factory(20)->create([
            'referral_code' => fn() => Str::upper(Str::random(8)),
        ]);

        // Create categories with specific data
        $categories = [
            ['name' => 'Software & Apps', 'icon' => '💻', 'description' => 'Desktop and mobile applications'],
            ['name' => 'Digital Downloads', 'icon' => '⬇️', 'description' => 'Files, documents, and media'],
            ['name' => 'Gaming Accounts', 'icon' => '🎮', 'description' => 'Game accounts and virtual items'],
            ['name' => 'Social Media', 'icon' => '📱', 'description' => 'Social media accounts and services'],
            ['name' => 'Streaming Services', 'icon' => '📺', 'description' => 'Premium streaming accounts'],
            ['name' => 'Education', 'icon' => '📚', 'description' => 'Courses, tutorials, and learning materials'],
            ['name' => 'Design Assets', 'icon' => '🎨', 'description' => 'Graphics, templates, and design resources'],
            ['name' => 'Music & Audio', 'icon' => '🎵', 'description' => 'Audio files, beats, and music'],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $createdCategories[] = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'icon' => $categoryData['icon'],
                'is_active' => true,
                'sort_order' => array_search($categoryData, $categories) + 1,
            ]);
        }

        // Create products for each seller
        $allSellers = collect([$admin, $seller1, $seller2])->merge($users->where('role', 'seller'));
        
        foreach ($allSellers as $seller) {
            Product::factory(random_int(2, 8))->create([
                'user_id' => $seller->id,
                'category_id' => $createdCategories[array_rand($createdCategories)]->id,
            ]);
        }

        // Create wallets for all users
        foreach ([$admin, $seller1, $seller2, $buyer] as $user) {
            Wallet::factory()->create([
                'user_id' => $user->id,
                'balance' => random_int(50, 1000),
                'total_earned' => random_int(0, 2000),
                'total_spent' => random_int(0, 500),
            ]);

            // Create transaction history
            WalletTransaction::factory(5)->create([
                'user_id' => $user->id,
            ]);

            // Create crypto deposits
            CryptoDeposit::factory(2)->create([
                'user_id' => $user->id,
            ]);
        }

        // Create some sample orders
        $products = Product::all();
        foreach ($products->random(10) as $product) {
            Order::factory()->create([
                'buyer_id' => $buyer->id,
                'seller_id' => $product->user_id,
                'product_id' => $product->id,
                'amount' => $product->price,
                'product_data' => $product->toArray(),
            ]);
        }

        $this->command->info('Database seeded successfully with marketplace data!');
        $this->command->info('Test accounts created:');
        $this->command->info('Admin: admin@digitalhub.com');
        $this->command->info('Seller: seller@digitalhub.com');
        $this->command->info('Buyer: buyer@digitalhub.com');
        $this->command->info('Password for all: password');
    }
}
