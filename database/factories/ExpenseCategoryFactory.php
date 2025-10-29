<?php

namespace Database\Factories;

use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseCategoryFactory extends Factory
{
    protected $model = ExpenseCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Medical Supplies', 'Utilities', 'Salaries', 'Maintenance', 'Equipment', 'Other']),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}

