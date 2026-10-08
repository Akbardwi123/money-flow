<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([Expense::TYPE_PRIORITY, Expense::TYPE_FLEXIBLE]);
        $category = $type === Expense::TYPE_PRIORITY
            ? fake()->randomElement(['Tempat Tinggal', 'Utilitas', 'Makanan Pokok', 'Asuransi & Proteksi'])
            : fake()->randomElement(['Kuliner & Nongkrong', 'Hiburan & Langganan', 'Hobi & Lifestyle']);

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'title' => fake()->words(3, true),
            'category' => $category,
            'amount' => fake()->numberBetween(50000, 3000000),
            'date' => fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            'notes' => fake()->sentence(),
        ];
    }
}
