<?php

namespace Database\seeders;

use App\Models\HospitalDepartment;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class HospitalDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Administration', 'code' => 'ADMIN', 'description' => 'Hospital administration and executive management'],
            ['name' => 'OPD', 'code' => 'OPD', 'description' => 'Outpatient Department - general consultations'],
            ['name' => 'Triage', 'code' => 'TRI', 'description' => 'Emergency triage and patient prioritization'],
            ['name' => 'Maternity', 'code' => 'MAT', 'description' => 'Maternity, ANC, labour and delivery'],
            ['name' => 'Theatre', 'code' => 'OT', 'description' => 'Operating theatre and surgical services'],
            ['name' => 'ICU/HDU', 'code' => 'ICU', 'description' => 'Intensive Care and High Dependency Unit'],
            ['name' => 'Paediatrics', 'code' => 'PAED', 'description' => 'Child health and paediatric services'],
            ['name' => 'Pharmacy', 'code' => 'PHARM', 'description' => 'Pharmacy and dispensing'],
            ['name' => 'Laboratory', 'code' => 'LAB', 'description' => 'Laboratory and pathology services'],
            ['name' => 'Radiology', 'code' => 'RAD', 'description' => 'Imaging and radiology services'],
            ['name' => 'Emergency', 'code' => 'ER', 'description' => 'Emergency department and ambulance'],
            ['name' => 'Ward/IPD', 'code' => 'IPD', 'description' => 'In-patient department and wards'],
            ['name' => 'CSSD', 'code' => 'CSSD', 'description' => 'Central Sterile Services Department'],
            ['name' => 'Mortuary', 'code' => 'MORT', 'description' => 'Mortuary and postmortem services'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Finance, billing and accounts'],
            ['name' => 'HR', 'code' => 'HR', 'description' => 'Human resources and staff management'],
            ['name' => 'IT', 'code' => 'IT', 'description' => 'Information technology and systems'],
            ['name' => 'Support Services', 'code' => 'SUP', 'description' => 'General support and ancillary services'],
        ];

        foreach ($departments as $department) {
            HospitalDepartment::updateOrCreate(
                ['name' => $department['name']],
                $department
            );
        }

        $roleMap = [
            'Super Admin' => 'Administration',
            'Hospital Admin' => 'Administration',
            'System Auditor' => 'Administration',
            'Marketing Manager' => 'Administration',
            'IT Support' => 'IT',
            'HR Officer' => 'HR',
            'Doctor' => 'OPD',
            'Telemedicine Doctor' => 'OPD',
            'Mental Health Professional' => 'OPD',
            'Nurse' => 'Ward/IPD',
            'Maternity Nurse' => 'Maternity',
            'ICU Nurse' => 'ICU/HDU',
            'Theatre Nurse' => 'Theatre',
            'Pharmacist' => 'Pharmacy',
            'Lab Technician' => 'Laboratory',
            'Radiologist' => 'Radiology',
            'Receptionist' => 'OPD',
            'Case Handler' => 'OPD',
            'Accountant' => 'Finance',
            'Procurement Officer' => 'Finance',
            'Inventory Manager' => 'Support Services',
            'Ambulance Operator' => 'Emergency',
            'CSSD Technician' => 'CSSD',
            'Mortuary Attendant' => 'Mortuary',
            'Security Officer' => 'Support Services',
            'Quality Officer' => 'Support Services',
            'Biomedical Engineer' => 'Support Services',
            'Dietitian' => 'Support Services',
            'Social Worker' => 'Support Services',
            'Support Staff' => 'Support Services',
            'Patient' => null,
            'System AI Bot' => null,
        ];

        foreach ($roleMap as $roleName => $departmentName) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                continue;
            }

            $role->department_id = $departmentName
                ? HospitalDepartment::where('name', $departmentName)->value('id')
                : null;
            $role->save();
        }

        $this->command->info('[OK] Seeded ' . count($departments) . ' hospital departments and linked roles.');
    }
}
