<?php

namespace Database\Factories;

use App\Models\BedAssignment;
use App\Models\Patient;
use App\Models\Bed;
use Illuminate\Database\Eloquent\Factories\Factory;

class BedAssignmentFactory extends Factory
{
    protected $model = BedAssignment::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'bed_id' => Bed::factory(),
            'assigned_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'discharged_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'discharged_at' => null,
        ]);
    }
}

