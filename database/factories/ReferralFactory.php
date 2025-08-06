<?php

namespace Database\Factories;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Referral>
 */
class ReferralFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'referrer_id' => User::factory(),
            'referee_id' => User::factory(),
            'referral_code' => Str::upper(Str::random(8)),
            'commission_rate' => $this->faker->randomFloat(2, 5, 15),
            'total_earned' => $this->faker->randomFloat(2, 0, 1000),
            'is_active' => true,
            'first_purchase_at' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}