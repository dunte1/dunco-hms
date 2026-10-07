<?php

namespace Database\Factories;

use App\Models\BackupRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BackupRecord>
 */
class BackupRecordFactory extends Factory
{
    protected $model = BackupRecord::class;

    public function definition(): array
    {
        return [
            'backup_type' => 'full',
            'backup_location' => 'DuncoHMS/' . fake()->unique()->dateTimeBetween('-30 days', 'now')->format('Y-m-d-H-i-s') . '.zip',
            'file_size_mb' => fake()->randomFloat(2, 0.5, 500),
            'started_at' => now()->subMinutes(5),
            'completed_at' => now()->subMinutes(2),
            'status' => 'completed',
            'verified' => false,
            'verified_at' => null,
            'notes' => fake()->optional()->sentence(),
            'created_at' => now()->subMinutes(2),
        ];
    }

    public function running(): static
    {
        return $this->state(fn () => [
            'status' => 'running',
            'completed_at' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn () => [
            'status' => 'failed',
            'completed_at' => now(),
            'notes' => 'Simulated failure',
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn () => [
            'verified' => true,
            'verified_at' => now(),
        ]);
    }
}
