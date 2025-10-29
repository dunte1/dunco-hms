<?php

namespace Database\Factories;

use App\Models\BedType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BedTypeFactory extends Factory
{
    protected $model = BedType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['General', 'ICU', 'Private', 'Semi-Private', 'Ward']),
            'charge_per_day' => $this->faker->randomFloat(2, 500, 5000),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}

