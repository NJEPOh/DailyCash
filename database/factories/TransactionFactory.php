<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = 'income';

        return [
            'account_id' => Account::factory(),
            'category_id' => Category::factory()->state(['type' => $type]),
            'type' => $type,
            'amount' => fake()->numberBetween(10, 500) * 1000,
            'date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'description' => fake()->sentence(4),
            'proof_image' => null,
        ];
    }

    /**
     * Indicate that the transaction is an income.
     */
    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
            'category_id' => Category::factory()->income(),
        ]);
    }

    /**
     * Indicate that the transaction is an expense.
     */
    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
            'category_id' => Category::factory()->expense(),
        ]);
    }
}
