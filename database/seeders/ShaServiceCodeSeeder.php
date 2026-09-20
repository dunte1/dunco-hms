<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShaServiceCodeSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('sha_service_codes')->count() > 0) {
            $this->command->info('[SKIP] sha_service_codes already seeded.');
            return;
        }

        $codes = [
            ['OPD01', 'General Outpatient Consultation', 'Standard outpatient consultation by a medical officer', 'Outpatient', 1200, false],
            ['OPD02', 'Specialist Consultation', 'Consultation by a specialist physician', 'Outpatient', 2500, false],
            ['IPD01', 'General Ward Admission (per day)', 'Daily rate for general ward admission', 'Inpatient', 3500, true],
            ['IPD02', 'ICU Admission (per day)', 'Daily rate for intensive care unit admission', 'Inpatient', 15000, true],
            ['IPD03', 'High Dependency Unit (per day)', 'Daily rate for high dependency unit', 'Inpatient', 8000, true],
            ['SUR01', 'Major Surgery', 'Major surgical procedure', 'Surgery', 40000, true],
            ['SUR02', 'Minor Surgery', 'Minor surgical procedure', 'Surgery', 15000, true],
            ['MAT01', 'Normal Delivery', 'Normal vaginal delivery including maternity care', 'Maternity', 12000, true],
            ['MAT02', 'Caesarean Section', 'Caesarean delivery including maternity care', 'Maternity', 30000, true],
            ['MAT03', 'Antenatal Care Visit', 'Routine antenatal care consultation', 'Maternity', 800, false],
            ['LAB01', 'Full Blood Count', 'Complete blood count laboratory test', 'Laboratory', 600, false],
            ['LAB02', 'Blood Glucose Test', 'Blood glucose measurement', 'Laboratory', 300, false],
            ['LAB03', 'Malaria Test', 'Malaria rapid diagnostic test', 'Laboratory', 400, false],
            ['LAB04', 'Urinalysis', 'Urine analysis test', 'Laboratory', 350, false],
            ['LAB05', 'HIV Test', 'HIV screening test', 'Laboratory', 500, false],
            ['RAD01', 'Chest X-Ray', 'Chest radiograph imaging', 'Radiology', 1200, false],
            ['RAD02', 'Ultrasound Scan', 'Abdominal/pelvic ultrasound', 'Radiology', 2000, false],
            ['RAD03', 'CT Scan', 'Computed tomography imaging', 'Radiology', 15000, true],
            ['RAD04', 'MRI Scan', 'Magnetic resonance imaging', 'Radiology', 25000, true],
            ['PHA01', 'Prescription Filling (per item)', 'Dispensing each prescription medicine item', 'Pharmacy', 100, false],
            ['EMR01', 'Emergency Consultation', 'Emergency department consultation', 'Emergency', 2000, false],
            ['EMR02', 'Emergency Resuscitation', 'Emergency resuscitation and stabilisation', 'Emergency', 5000, true],
            ['DIA01', 'Dialysis Session', 'Single haemodialysis session', 'Dialysis', 8000, true],
            ['CHE01', 'Chemotherapy Session', 'Single chemotherapy administration', 'Oncology', 18000, true],
            ['PHY01', 'Physiotherapy Session', 'Physiotherapy treatment session', 'Rehabilitation', 1500, false],
        ];

        $now = now();
        $rows = array_map(fn ($row) => [
            'code' => $row[0],
            'name' => $row[1],
            'description' => $row[2],
            'category' => $row[3],
            'tariff_amount' => $row[4],
            'requires_authorization' => $row[5],
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ], $codes);

        DB::table('sha_service_codes')->insert($rows);

        $this->command->info('[OK] Seeded ' . count($codes) . ' SHA service codes.');
    }
}
