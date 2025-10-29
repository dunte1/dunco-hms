<?php

namespace Database\Factories;

use App\Models\Bed;
use App\Models\BedType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BedFactory extends Factory
{
    protected $model = Bed::class;

    public function definition(): array
    {
        return [
            'bed_number' => $this->faker->unique()->bothify('BED-###'),
            'ward_name' => $this->faker->randomElement(['General Ward', 'ICU Ward', 'Maternity Ward', 'Pediatric Ward', 'Surgical Ward']),
            'bed_type_id' => BedType::factory(),
            'is_available' => $this->faker->boolean(70),
        ];
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => true,
        ]);
    }

    public function occupied(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }
}

