<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        $type = $this->faker->randomElement(['downloadable', 'account']);
        
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(3),
            'images' => null,
            'price' => $this->faker->randomFloat(2, 5, 500),
            'type' => $type,
            'download_file' => $type === 'downloadable' ? '/downloads/' . Str::slug($title) . '.zip' : null,
            'account_details' => $type === 'account' ? $this->faker->sentence() : null,
            'is_active' => true,
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'stock_quantity' => $this->faker->numberBetween(1, 100),
            'sold_count' => $this->faker->numberBetween(0, 50),
            'rating' => $this->faker->randomFloat(2, 3, 5),
            'review_count' => $this->faker->numberBetween(0, 25),
            'tags' => $this->faker->randomElements([
                'popular', 'trending', 'premium', 'bestseller', 'new', 'verified'
            ], random_int(0, 3)),
        ];
    }
}