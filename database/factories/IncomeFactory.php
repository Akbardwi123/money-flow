<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Income>
 */
class IncomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement(['Gaji Bulanan', 'Project Web Development', 'Dividen Saham', 'Bonus Kinerja']),
            'source' => fake()->randomElement(['Gaji Pokok', 'Freelance', 'Dividen / Passive Income', 'Bisnis / Usaha']),
            'amount' => fake()->numberBetween(1000000, 15000000),
            'date' => fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            'notes' => fake()->sentence(),
        ];
    }
}
