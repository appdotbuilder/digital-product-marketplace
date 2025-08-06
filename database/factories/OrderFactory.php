<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']);
        $escrowStatus = $this->faker->randomElement(['none', 'held', 'released']);
        
        return [
            'order_number' => 'ORD-' . Str::upper(Str::random(10)),
            'buyer_id' => User::factory(),
            'seller_id' => User::factory(),
            'product_id' => Product::factory(),
            'amount' => $this->faker->randomFloat(2, 5, 500),
            'status' => $status,
            'escrow_status' => $escrowStatus,
            'escrow_release_at' => $escrowStatus === 'held' ? $this->faker->dateTimeBetween('now', '+7 days') : null,
            'buyer_notes' => $this->faker->optional()->sentence(),
            'seller_notes' => $this->faker->optional()->sentence(),
            'admin_notes' => $this->faker->optional()->sentence(),
            'product_data' => [
                'title' => $this->faker->sentence(3),
                'price' => $this->faker->randomFloat(2, 5, 500),
                'type' => $this->faker->randomElement(['downloadable', 'account']),
            ],
            'delivery_data' => $this->faker->optional()->passthrough([
                'download_url' => '/downloads/file.zip',
                'instructions' => 'Download instructions',
            ]),
            'delivered_at' => $status === 'completed' ? $this->faker->dateTimeBetween('-30 days', 'now') : null,
            'completed_at' => $status === 'completed' ? $this->faker->dateTimeBetween('-30 days', 'now') : null,
        ];
    }
}