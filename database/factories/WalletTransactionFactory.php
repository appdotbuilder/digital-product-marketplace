<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WalletTransaction>
 */
class WalletTransactionFactory extends Factory
{
    protected $model = WalletTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement([
            'deposit', 'withdrawal', 'purchase', 'sale', 'referral_bonus', 'escrow_hold', 'escrow_release'
        ]);
        
        $amount = $this->faker->randomFloat(2, -500, 500);
        $balanceAfter = $this->faker->randomFloat(2, 0, 1000);
        
        $descriptions = [
            'deposit' => 'Crypto deposit',
            'withdrawal' => 'Withdrawal request',
            'purchase' => 'Product purchase',
            'sale' => 'Product sale',
            'referral_bonus' => 'Referral commission',
            'escrow_hold' => 'Escrow hold',
            'escrow_release' => 'Escrow release',
        ];
        
        return [
            'user_id' => User::factory(),
            'transaction_id' => 'TXN-' . Str::upper(Str::random(12)),
            'type' => $type,
            'amount' => $amount,
            'balance_after' => $balanceAfter,
            'description' => $descriptions[$type] ?? 'Transaction',
            'metadata' => null,
            'status' => 'completed',
        ];
    }
}