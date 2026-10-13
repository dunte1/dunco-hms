<?php

namespace Database\Seeders;

use App\Models\KpiDefinition;
use Illuminate\Database\Seeder;

class KpiDefinitionSeeder extends Seeder
{
    public function run(): void
    {
        $kpis = [
            ['code' => 'OPD_VOLUMES', 'name' => 'OPD Visit Volumes', 'unit' => 'visits', 'target_value' => 100, 'source_module' => 'opd'],
            ['code' => 'IPD_ADMIT', 'name' => 'IPD Admissions', 'unit' => 'admissions', 'target_value' => 20, 'source_module' => 'ipd'],
            ['code' => 'REVENUE_MONTH', 'name' => 'Monthly Revenue', 'unit' => 'KES', 'target_value' => 500000, 'source_module' => 'finance'],
            ['code' => 'BED_OCCUPANCY', 'name' => 'Bed Occupancy Rate', 'unit' => '%', 'target_value' => 70, 'source_module' => 'beds'],
            ['code' => 'AVG_WAIT', 'name' => 'Average Wait Time', 'unit' => 'minutes', 'target_value' => 30, 'source_module' => 'queue'],
            ['code' => 'PHARM_FULFILL', 'name' => 'Pharmacy Fulfilment Rate', 'unit' => '%', 'target_value' => 95, 'source_module' => 'pharmacy'],
        ];

        foreach ($kpis as $row) {
            KpiDefinition::firstOrCreate(
                ['code' => $row['code']],
                [
                    'name' => $row['name'],
                    'description' => $row['name'] . ' performance indicator',
                    'source_module' => $row['source_module'],
                    'target_value' => $row['target_value'],
                    'unit' => $row['unit'],
                    'is_active' => true,
                ]
            );
        }

        $this->command?->info('[OK] KPI definitions: ' . KpiDefinition::count());
    }
}
