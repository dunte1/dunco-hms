<?php

namespace Database\Factories;

use App\Models\DoctorDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorDepartmentFactory extends Factory
{
    protected $model = DoctorDepartment::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company() . ' Department',
            'description' => $this->faker->sentence(),
        ];
    }
}

