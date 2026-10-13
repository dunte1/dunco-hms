<?php

namespace Database\Seeders;

use App\Models\BiAnalytic;
use Illuminate\Database\Seeder;

class BiAnalyticSeeder extends Seeder
{
    public function run(): void
    {
        if (BiAnalytic::count() > 0) {
            $this->command?->info('[SKIP] bi_analytics already seeded.');
            return;
        }

        $revenuePoints = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $base = 45000 + (30 - $i) * 850 + random_int(-2500, 4000);
            $revenuePoints[] = ['date' => $date, 'value' => round($base, 2), 'type' => 'revenue'];
        }

        $patientPoints = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $base = 40 + (30 - $i) * 2 + random_int(-5, 8);
            $patientPoints[] = ['date' => $date, 'value' => max(5, $base), 'type' => 'patient_count'];
        }

        $occupancyPoints = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $rate = 45 + (30 - $i) * 1.2 + random_int(-6, 10);
            $occupancyPoints[] = ['date' => $date, 'value' => min(98, max(20, round($rate, 1))), 'type' => 'occupancy'];
        }

        $rows = [
            [
                'metric_name' => 'Revenue Analytics',
                'metric_type' => 'revenue',
                'data_points' => $revenuePoints,
                'date_from' => now()->subDays(30)->toDateString(),
                'date_to' => now()->toDateString(),
                'granularity' => 'daily',
                'predictions' => [
                    ['period' => 1, 'predicted_value' => 72000, 'confidence' => 88],
                    ['period' => 2, 'predicted_value' => 74500, 'confidence' => 78],
                    ['period' => 3, 'predicted_value' => 76000, 'confidence' => 68],
                ],
                'insights' => '30-day revenue trending upward. Peak mid-month collections. Next month forecast ~$72K.',
            ],
            [
                'metric_name' => 'Patient Count Analytics',
                'metric_type' => 'patient_count',
                'data_points' => $patientPoints,
                'date_from' => now()->subDays(30)->toDateString(),
                'date_to' => now()->toDateString(),
                'granularity' => 'daily',
                'predictions' => [
                    ['period' => 1, 'predicted_value' => 95, 'confidence' => 82],
                    ['period' => 2, 'predicted_value' => 98, 'confidence' => 74],
                ],
                'insights' => 'Patient volumes steady with slight growth. OPD mornings busiest.',
            ],
            [
                'metric_name' => 'Bed Occupancy Analytics',
                'metric_type' => 'occupancy',
                'data_points' => $occupancyPoints,
                'date_from' => now()->subDays(30)->toDateString(),
                'date_to' => now()->toDateString(),
                'granularity' => 'daily',
                'predictions' => [
                    ['period' => 1, 'predicted_value' => 72.5, 'confidence' => 90],
                    ['period' => 2, 'predicted_value' => 74.0, 'confidence' => 80],
                ],
                'insights' => 'Occupancy averaging ~70%. ICU and maternity highest demand. Capacity review recommended.',
            ],
        ];

        foreach ($rows as $row) {
            BiAnalytic::create($row);
        }

        $this->command?->info('[OK] Seeded ' . count($rows) . ' BI analytics reports.');
    }
}
