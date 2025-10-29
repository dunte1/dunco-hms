<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'expense_category_id' => ExpenseCategory::factory(),
            'expense_number' => 'EXP' . $this->faker->unique()->numerify('#######'),
            'vendor_name' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat(2, 50, 5000),
            'expense_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'bank_transfer', 'check']),
            'reference_number' => $this->faker->optional()->numerify('REF#######'),
            'status' => $this->faker->randomElement(['pending', 'paid', 'cancelled']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}

