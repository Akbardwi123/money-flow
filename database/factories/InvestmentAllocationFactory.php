<?php

namespace Database\Factories;

use App\Models\InvestmentAllocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvestmentAllocation>
 */
class InvestmentAllocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([InvestmentAllocation::TYPE_FINANCIAL, InvestmentAllocation::TYPE_SKILL]);

        $category = $type === InvestmentAllocation::TYPE_FINANCIAL
            ? fake()->randomElement(['Saham', 'Crypto', 'Reksa Dana', 'Obligasi / SBN'])
            : fake()->randomElement(['Kursus / Lab Cybersecurity', 'Sertifikasi Internasional', 'Buku / Literatur']);

        $platform = $type === InvestmentAllocation::TYPE_FINANCIAL
            ? fake()->randomElement(['Stockbit', 'Bibit', 'Tokocrypto', 'Binance'])
            : fake()->randomElement(['HackTheBox', 'OffSec', 'Pearson VUE', 'Gramedia']);

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'title' => fake()->words(3, true),
            'category' => $category,
            'platform' => $platform,
            'amount' => fake()->numberBetween(500000, 5000000),
            'date' => fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            'target_objective' => fake()->sentence(),
            'status' => $type === InvestmentAllocation::TYPE_SKILL ? 'in_progress' : 'active',
            'notes' => fake()->sentence(),
        ];
    }
}
