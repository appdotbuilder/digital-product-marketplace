<?php

namespace Database\Factories;

use App\Models\CryptoDeposit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CryptoDeposit>
 */
class CryptoDepositFactory extends Factory
{
    protected $model = CryptoDeposit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $crypto = $this->faker->randomElement(['BTC', 'ETH', 'USDT']);
        $usdAmount = $this->faker->randomFloat(2, 10, 1000);
        
        $rates = ['BTC' => 45000, 'ETH' => 3000, 'USDT' => 1];
        $rate = $rates[$crypto];
        $cryptoAmount = $usdAmount / $rate;
        
        return [
            'user_id' => User::factory(),
            'transaction_hash' => 'demo-' . Str::upper(Str::random(20)),
            'cryptocurrency' => $crypto,
            'crypto_amount' => $cryptoAmount,
            'usd_amount' => $usdAmount,
            'exchange_rate' => $rate,
            'wallet_address' => $crypto . '-' . Str::random(20),
            'confirmations' => $this->faker->numberBetween(0, 10),
            'required_confirmations' => 3,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'failed']),
            'expires_at' => $this->faker->dateTimeBetween('now', '+2 hours'),
            'raw_transaction_data' => null,
        ];
    }


}