<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalEarned = $this->faker->randomFloat(2, 0, 5000);
        $totalSpent = $this->faker->randomFloat(2, 0, 2000);
        $balance = $this->faker->randomFloat(2, 0, 1000);
        
        return [
            'user_id' => User::factory(),
            'balance' => $balance,
            'pending_balance' => $this->faker->randomFloat(2, 0, 200),
            'total_earned' => $totalEarned,
            'total_spent' => $totalSpent,
        ];
    }
}