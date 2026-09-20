<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            ['name' => 'Dashboard', 'category' => 'Core', 'sort' => 1],
            ['name' => 'Patients Management', 'category' => 'Hospital Management', 'sort' => 10],
            ['name' => 'Appointments', 'category' => 'Hospital Management', 'sort' => 11],
            ['name' => 'IPD (In Patient Department)', 'category' => 'Hospital Management', 'sort' => 12],
            ['name' => 'OPD (Out Patient Department)', 'category' => 'Hospital Management', 'sort' => 13],
            ['name' => 'Patient Admissions', 'category' => 'Hospital Management', 'sort' => 14],
            ['name' => 'Doctors Management', 'category' => 'Hospital Management', 'sort' => 15],
            ['name' => 'Doctors Departments', 'category' => 'Hospital Management', 'sort' => 16],
            ['name' => 'Doctor OPD Charge', 'category' => 'Hospital Management', 'sort' => 17],
            ['name' => 'Schedules', 'category' => 'Hospital Management', 'sort' => 18],
            ['name' => 'Nurses Management', 'category' => 'Hospital Management', 'sort' => 19],
            ['name' => 'Receptionists', 'category' => 'Hospital Management', 'sort' => 20],
            ['name' => 'Accountants', 'category' => 'Hospital Management', 'sort' => 21],
            ['name' => 'Pharmacists', 'category' => 'Hospital Management', 'sort' => 22],
            ['name' => 'Lab Technician', 'category' => 'Hospital Management', 'sort' => 23],
            ['name' => 'Advance Payments', 'category' => 'Finance', 'sort' => 30],
            ['name' => 'Accounts', 'category' => 'Finance', 'sort' => 31],
            ['name' => 'Billing', 'category' => 'Finance', 'sort' => 32],
            ['name' => 'Invoices', 'category' => 'Finance', 'sort' => 33],
            ['name' => 'Payments', 'category' => 'Finance', 'sort' => 34],
            ['name' => 'Payment Reports', 'category' => 'Finance', 'sort' => 35],
            ['name' => 'Manual Billing Payments', 'category' => 'Finance', 'sort' => 36],
            ['name' => 'Expenses Management', 'category' => 'Finance', 'sort' => 37],
            ['name' => 'Income Management', 'category' => 'Finance', 'sort' => 38],
            ['name' => 'Payrolls', 'category' => 'Finance', 'sort' => 39],
            ['name' => 'Hospital Charges', 'category' => 'Finance', 'sort' => 40],
            ['name' => 'Hospital Charges Categories', 'category' => 'Finance', 'sort' => 41],
            ['name' => 'Beds Management', 'category' => 'Clinical', 'sort' => 50],
            ['name' => 'Bed Assigns', 'category' => 'Clinical', 'sort' => 51],
            ['name' => 'Beds Visulization', 'category' => 'Clinical', 'sort' => 52],
            ['name' => 'Bed Status', 'category' => 'Clinical', 'sort' => 53],
            ['name' => 'Bed Types', 'category' => 'Clinical', 'sort' => 54],
            ['name' => 'Birth Reports', 'category' => 'Clinical', 'sort' => 55],
            ['name' => 'Death Reports', 'category' => 'Clinical', 'sort' => 56],
            ['name' => 'Operation Reports', 'category' => 'Clinical', 'sort' => 57],
            ['name' => 'Investigation Reports', 'category' => 'Clinical', 'sort' => 58],
            ['name' => 'Blood Bank', 'category' => 'Clinical', 'sort' => 59],
            ['name' => 'Blood Donors', 'category' => 'Clinical', 'sort' => 60],
            ['name' => 'Case Handlers', 'category' => 'Clinical', 'sort' => 61],
            ['name' => 'Cases Management', 'category' => 'Clinical', 'sort' => 62],
            ['name' => 'Patient Diagnosis Categories', 'category' => 'Clinical', 'sort' => 63],
            ['name' => 'Patient Diagnosis Reports', 'category' => 'Clinical', 'sort' => 64],
            ['name' => 'Prescriptions Management', 'category' => 'Clinical', 'sort' => 65],
            ['name' => 'Medicines (+ Inventory)', 'category' => 'Inventory', 'sort' => 70],
            ['name' => 'Medicines Brands', 'category' => 'Inventory', 'sort' => 71],
            ['name' => 'Medicines Categories', 'category' => 'Inventory', 'sort' => 72],
            ['name' => 'Full Inventory Management', 'category' => 'Inventory', 'sort' => 73],
            ['name' => 'Pathology Categories', 'category' => 'Diagnostics', 'sort' => 80],
            ['name' => 'Pathology Tests', 'category' => 'Diagnostics', 'sort' => 81],
            ['name' => 'Radiology Categories', 'category' => 'Diagnostics', 'sort' => 82],
            ['name' => 'Radiology Tests', 'category' => 'Diagnostics', 'sort' => 83],
            ['name' => 'Insurance Management', 'category' => 'Insurance', 'sort' => 90],
            ['name' => 'SHA / SHIF', 'category' => 'Insurance', 'sort' => 91],
            ['name' => 'Packages Management', 'category' => 'Insurance', 'sort' => 92],
            ['name' => 'Ambulance', 'category' => 'Operations', 'sort' => 100],
            ['name' => 'Ambulance Calls', 'category' => 'Operations', 'sort' => 101],
            ['name' => 'Queue Management', 'category' => 'Operations', 'sort' => 102],
            ['name' => 'Visitor Management', 'category' => 'Operations', 'sort' => 103],
            ['name' => 'Notice Board', 'category' => 'Communications', 'sort' => 110],
            ['name' => 'Send Mails', 'category' => 'Communications', 'sort' => 111],
            ['name' => 'SMS Reminders', 'category' => 'Communications', 'sort' => 112],
            ['name' => 'Inquiry', 'category' => 'Communications', 'sort' => 113],
            ['name' => 'Settings', 'category' => 'System', 'sort' => 120],
            ['name' => 'Frontend CMS', 'category' => 'System', 'sort' => 121],
            ['name' => 'Multi-Lingual', 'category' => 'System', 'sort' => 122],
            ['name' => 'Multi-Currency', 'category' => 'System', 'sort' => 123],
            ['name' => 'Export of Everything', 'category' => 'System', 'sort' => 124],
            ['name' => 'Roles + ALC for 8 Different Departments', 'category' => 'System', 'sort' => 125],
            ['name' => 'Documents', 'category' => 'HR', 'sort' => 130],
            ['name' => 'Document Types', 'category' => 'HR', 'sort' => 131],
            ['name' => 'HR Management', 'category' => 'HR', 'sort' => 132],
            ['name' => 'Reports & Analytics', 'category' => 'System', 'sort' => 126],
            ['name' => 'Communication & Frontdesk', 'category' => 'Communications', 'sort' => 114],
            ['name' => 'Biometric Security', 'category' => 'Security', 'sort' => 140],
            ['name' => 'DHA Integration', 'category' => 'Integration', 'sort' => 150],
            ['name' => 'Telemedicine', 'category' => 'Clinical', 'sort' => 66],
            ['name' => 'RFID & IoT', 'category' => 'Operations', 'sort' => 104],
        ];

        $now = now();

        foreach ($modules as $m) {
            $slug = str($m['name'])->slug('-')->toString();
            $existing = DB::table('modules')->where('slug', $slug)->first();

            if ($existing) {
                DB::table('modules')->where('id', $existing->id)->update([
                    'name' => $m['name'],
                    'category' => $m['category'],
                    'sort_order' => $m['sort'],
                    'updated_at' => $now,
                ]);
            } else {
                DB::table('modules')->insert([
                    'name' => $m['name'],
                    'slug' => $slug,
                    'category' => $m['category'],
                    'is_enabled' => true,
                    'sort_order' => $m['sort'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('[OK] Seeded module registry (' . count($modules) . ' modules).');
    }
}
