<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\DoctorDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'doctor_department_id' => DoctorDepartment::factory(),
            'qualification' => $this->faker->randomElement(['MBBS', 'MD', 'MS', 'DM']),
            'years_experience' => $this->faker->numberBetween(1, 30),
        ];
    }
}

