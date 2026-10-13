<?php

namespace Database\Seeders;

use App\Models\InsuranceProvider;
use Illuminate\Database\Seeder;

class InsuranceProviderSeeder extends Seeder
{
    /**
     * Seed common Kenyan insurers + clergy/religious mission schemes.
     */
    public function run(): void
    {
        $providers = [
            // National / SHA
            ['name' => 'Social Health Authority (SHA)', 'code' => 'SHA', 'prefix' => 'SHA', 'coverage_percentage' => 100, 'notes' => 'National SHA/SHIF scheme. Claims via EHA when configured.'],
            ['name' => 'NHIF (Legacy)', 'code' => 'NHIF_LEGACY', 'prefix' => 'NHIF', 'coverage_percentage' => 80, 'notes' => 'Legacy NHIF members during SHA transition.'],

            // Private insurers
            ['name' => 'Britam', 'code' => 'BRITAM', 'prefix' => 'BRITAM', 'coverage_percentage' => 80, 'notes' => 'Britam Healthcare. Configure API endpoint when available.'],
            ['name' => 'CIC Insurance Group', 'code' => 'CIC', 'prefix' => 'CIC', 'coverage_percentage' => 80, 'notes' => 'CIC Group health cover.'],
            ['name' => 'Jubilee Insurance', 'code' => 'JUBILEE', 'prefix' => 'JUBILEE', 'coverage_percentage' => 80, 'notes' => 'Jubilee Health.'],
            ['name' => 'Resolution Health', 'code' => 'RESOLUTION', 'prefix' => 'RESOL', 'coverage_percentage' => 80, 'notes' => 'Resolution Health East Africa.'],
            ['name' => 'AAR Healthcare', 'code' => 'AAR', 'prefix' => 'AAR', 'coverage_percentage' => 80, 'notes' => 'AAR Healthcare Kenya.'],
            ['name' => 'Minet Kenya', 'code' => 'MINET', 'prefix' => 'MINET', 'coverage_percentage' => 85, 'notes' => 'Minet / corporate health schemes.'],
            ['name' => 'Madison Insurance', 'code' => 'MADISON', 'prefix' => 'MADISON', 'coverage_percentage' => 75, 'notes' => 'Madison General Insurance.'],
            ['name' => 'UAP Old Mutual', 'code' => 'UAP', 'prefix' => 'UAP', 'coverage_percentage' => 80, 'notes' => 'UAP Old Mutual health.'],
            ['name' => 'Occidental Insurance', 'code' => 'OCCIDENTAL', 'prefix' => 'OCCID', 'coverage_percentage' => 75, 'notes' => 'Occidental Insurance Kenya.'],
            ['name' => 'Apollo Hospitals (Kenya)', 'code' => 'APOLLO', 'prefix' => 'APOLLO', 'coverage_percentage' => 80, 'notes' => 'Apollo / corporate schemes.'],

            // Clergy / religious mission schemes
            ['name' => 'Clergy / Mission Insurance Scheme', 'code' => 'CLERGY', 'prefix' => 'CLERGY', 'coverage_percentage' => 90, 'notes' => 'For priests, sisters, brothers, and religious institute staff. Scheme admin covers premiums.'],
            ['name' => 'Religious Institutes Health Fund', 'code' => 'RELIGIOUS_FUND', 'prefix' => 'RELFUND', 'coverage_percentage' => 85, 'notes' => 'Congregation / diocese health fund for religious personnel.'],
            ['name' => 'Parish Health Mutual', 'code' => 'PARISH_MUTUAL', 'prefix' => 'PARISH', 'coverage_percentage' => 70, 'notes' => 'Parish-level mutual aid for clergy and lay workers.'],
        ];

        foreach ($providers as $row) {
            InsuranceProvider::firstOrCreate(
                ['code' => $row['code']],
                [
                    'name' => $row['name'],
                    'description' => $row['notes'],
                    'contact_person' => null,
                    'phone' => null,
                    'email' => 'insurance@duncohms.local',
                    'address' => 'Kenya',
                    'coverage_percentage' => $row['coverage_percentage'],
                    'coverage_limit' => null,
                    'copayment_percentage' => max(0, 100 - $row['coverage_percentage']),
                    'deductible_amount' => 0,
                    'policy_number_prefix' => substr($row['prefix'], 0, 10),
                    'is_active' => true,
                    'notes' => $row['notes'],
                ]
            );
        }

        $this->command?->info('[OK] Insurance providers: ' . InsuranceProvider::count() . ' total.');
    }
}
