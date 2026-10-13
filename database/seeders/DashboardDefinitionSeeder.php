<?php

namespace Database\Seeders;

use App\Models\DashboardDefinition;
use Illuminate\Database\Seeder;

class DashboardDefinitionSeeder extends Seeder
{
    public function run(): void
    {
        $defs = [
            [
                'name' => 'Operations Overview',
                'slug' => 'operations-overview',
                'description' => 'Daily hospital operations: OPD, queue, admissions, beds.',
                'layout' => ['cards' => ['opd_visits', 'queue_waiting', 'admissions', 'bed_occupancy']],
                'status' => 'published',
                'is_default' => true,
            ],
            [
                'name' => 'Revenue Analytics',
                'slug' => 'revenue-analytics',
                'description' => 'Revenue trends, collections, and growth metrics.',
                'layout' => ['charts' => ['revenue_trend', 'collection_rate'], 'period' => 'monthly'],
                'status' => 'published',
                'is_default' => false,
            ],
            [
                'name' => 'Patient Flow',
                'slug' => 'patient-flow',
                'description' => 'Patient registration, triage, consultation, and pharmacy flow.',
                'layout' => ['cards' => ['new_patients', 'triaged', 'in_consultation', 'pharmacy_pending']],
                'status' => 'published',
                'is_default' => false,
            ],
            [
                'name' => 'Clinical Quality',
                'slug' => 'clinical-quality',
                'description' => 'Clinical outcomes, diagnosis mix, and quality indicators.',
                'layout' => ['cards' => ['avg_los', 'readmission_rate', 'diagnosis_mix']],
                'status' => 'draft',
                'is_default' => false,
            ],
        ];

        foreach ($defs as $row) {
            DashboardDefinition::firstOrCreate(
                ['slug' => $row['slug']],
                [
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'layout' => $row['layout'],
                    'status' => $row['status'],
                    'is_default' => $row['is_default'],
                    'owner_id' => 1,
                ]
            );
        }

        $this->command?->info('[OK] Dashboard definitions: ' . DashboardDefinition::count());
    }
}
