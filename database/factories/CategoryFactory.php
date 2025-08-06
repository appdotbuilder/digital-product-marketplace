<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
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

        $category = $this->faker->randomElement($categories);
        
        return [
            'name' => $category['name'],
            'slug' => Str::slug($category['name']),
            'description' => $category['description'],
            'icon' => $category['icon'],
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}