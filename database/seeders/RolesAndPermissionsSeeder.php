<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $this->createPermissions();

        // Create roles
        $this->createRoles();

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    private function createPermissions(): void
    {
        $permissions = [
            // Patient Management
            'view patients', 'add patients', 'edit patients', 'delete patients',
            'manage admissions', 'manage discharges', 'assign beds', 'upload documents',
            
            // Appointments & Scheduling
            'create appointments', 'manage appointments', 'reschedule appointments', 'cancel appointments',
            'view doctor schedules', 'send appointment reminders',
            
            // Prescriptions & Medicines
            'create prescriptions', 'edit prescriptions', 'view prescriptions', 'dispense medicines',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'generate expiry alerts', 'generate stock alerts',
            
            // Lab & Radiology
            'manage test categories', 'add test requests', 'enter test results', 'approve test results',
            'print lab reports', 'download lab reports', 'view test results',
            
            // Billing & Finance
            'create invoices', 'edit invoices', 'add payments', 'add refunds',
            'manage expenses', 'manage income', 'view payment reports', 'manage insurance claims',
            'manage packages',
            
            // IPD/OPD
            'admit patients', 'discharge patients', 'manage patient vitals', 'manage patient notes',
            'assign doctors', 'assign nurses', 'update bed status',
            
            // Doctors & Staff
            'manage staff profiles', 'assign departments', 'view attendance', 'manage salaries',
            'manage payrolls',
            
            // Bed & Room Management
            'create bed types', 'edit bed types', 'view bed status', 'manage bed assignments',
            
            // Accounting & Payments
            'manage receipts', 'handle advance payments', 'view financial reports',
            'export ledgers', 'export journals',
            
            // Reports
            'generate patient reports', 'generate billing reports', 'generate pathology reports',
            'generate operation reports', 'generate birth reports', 'generate death reports',
            'generate financial reports', 'export reports', 'view dashboard analytics',
            
            // Settings
            'manage hospital info', 'manage notification templates', 'manage roles', 'manage permissions',
            
            // CMS & Communication
            'manage homepage', 'manage services', 'manage doctors listing', 'send mass mails',
            'send mass sms', 'manage inquiries', 'manage contact messages', 'manage notice board',
            
            // Multi-Hospital / Tenancy
            'manage hospital branches', 'assign staff per branch', 'view branch analytics',
            
            // AI & Advanced Features
            'use ai assistant', 'manage ai suggestions', 'view analytics', 'manage integrations',
            'use telemedicine', 'manage rfid tags', 'monitor iot sensors',
            
            // System Administration
            'view audit logs', 'manage system settings', 'manage backups', 'view system logs',
            'manage api tokens', 'manage user accounts',
            
            // Marketing Suite
            'manage marketing', 'create marketing posts', 'edit marketing posts', 'delete marketing posts',
            'approve marketing posts', 'manage campaigns', 'manage social accounts', 'schedule posts',
            'manage comment replies', 'manage graphic assets', 'access marketing analytics', 'manage seo',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }

    private function createRoles(): void
    {
        $roles = [
            'Super Admin',
            'Hospital Admin',
            'Doctor',
            'Nurse',
            'Receptionist',
            'Pharmacist',
            'Lab Technician',
            'Radiologist',
            'Accountant',
            'Case Handler',
            'Ambulance Operator',
            'HR Officer',
            'Patient',
            'System Auditor',
            'Support Staff',
            'Telemedicine Doctor',
            'Inventory Manager',
            'Procurement Officer',
            'IT Support',
            'Marketing Manager',
            'System AI Bot',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    private function assignPermissionsToRoles(): void
    {
        // Get all permissions
        $allPermissions = Permission::all();
        $permissionNames = $allPermissions->pluck('name')->toArray();

        // Super Admin - Full access to everything
        $superAdmin = Role::findByName('Super Admin');
        $superAdmin->givePermissionTo($allPermissions);

        // Hospital Admin - Manage hospital operations
        $hospitalAdmin = Role::findByName('Hospital Admin');
        $hospitalAdminPermissions = array_intersect($permissionNames, [
            'view patients', 'add patients', 'edit patients', 'delete patients',
            'manage admissions', 'manage discharges', 'assign beds', 'upload documents',
            'create appointments', 'manage appointments', 'reschedule appointments', 'cancel appointments',
            'view doctor schedules', 'send appointment reminders',
            'create prescriptions', 'edit prescriptions', 'view prescriptions',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'generate expiry alerts', 'generate stock alerts',
            'manage test categories', 'add test requests', 'enter test results', 'approve test results',
            'print lab reports', 'download lab reports',
            'create invoices', 'edit invoices', 'add payments', 'add refunds',
            'manage expenses', 'manage income', 'view payment reports', 'manage insurance claims',
            'manage packages',
            'admit patients', 'discharge patients', 'manage patient vitals', 'manage patient notes',
            'assign doctors', 'assign nurses', 'update bed status',
            'manage staff profiles', 'assign departments', 'view attendance', 'manage salaries',
            'manage payrolls',
            'create bed types', 'edit bed types', 'view bed status', 'manage bed assignments',
            'manage receipts', 'handle advance payments', 'view financial reports',
            'export ledgers', 'export journals',
            'generate patient reports', 'generate billing reports', 'generate pathology reports',
            'generate operation reports', 'generate birth reports', 'generate death reports',
            'export reports', 'view dashboard analytics',
            'manage hospital info', 'manage notification templates',
            'manage homepage', 'manage services', 'manage doctors listing', 'send mass mails',
            'send mass sms', 'manage inquiries', 'manage contact messages', 'manage notice board',
            'manage hospital branches', 'assign staff per branch', 'view branch analytics',
            'use ai assistant', 'manage ai suggestions', 'view analytics', 'manage integrations',
            'use telemedicine', 'manage rfid tags', 'monitor iot sensors',
            'view audit logs', 'manage system settings', 'manage backups', 'view system logs',
            'manage api tokens', 'manage user accounts',
        ]);
        $hospitalAdmin->givePermissionTo($hospitalAdminPermissions);

        // Doctor - Patient care and medical operations
        $doctor = Role::findByName('Doctor');
        $doctorPermissions = array_intersect($permissionNames, [
            'view patients', 'edit patients', 'upload documents',
            'create appointments', 'manage appointments', 'view doctor schedules',
            'create prescriptions', 'edit prescriptions', 'view prescriptions',
            'add test requests', 'view test results', 'print lab reports', 'download lab reports',
            'view payment reports',
            'admit patients', 'discharge patients', 'manage patient vitals', 'manage patient notes',
            'assign nurses', 'update bed status',
            'view staff profiles', 'view attendance',
            'view bed status', 'manage bed assignments',
            'generate patient reports', 'generate pathology reports', 'generate operation reports',
            'view dashboard analytics',
            'use ai assistant', 'view analytics', 'use telemedicine',
        ]);
        $doctor->givePermissionTo($doctorPermissions);

        // Nurse - Patient care and assistance
        $nurse = Role::findByName('Nurse');
        $nursePermissions = array_intersect($permissionNames, [
            'view patients', 'edit patients', 'upload documents',
            'view appointments', 'view doctor schedules',
            'view prescriptions',
            'view test results', 'print lab reports', 'download lab reports',
            'view payment reports',
            'admit patients', 'discharge patients', 'manage patient vitals', 'manage patient notes',
            'update bed status',
            'view staff profiles', 'view attendance',
            'view bed status', 'manage bed assignments',
            'generate patient reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
        ]);
        $nurse->givePermissionTo($nursePermissions);

        // Receptionist - Front desk operations
        $receptionist = Role::findByName('Receptionist');
        $receptionistPermissions = array_intersect($permissionNames, [
            'view patients', 'add patients', 'edit patients', 'upload documents',
            'create appointments', 'manage appointments', 'reschedule appointments', 'cancel appointments',
            'view doctor schedules', 'send appointment reminders',
            'view prescriptions',
            'view test results', 'print lab reports', 'download lab reports',
            'create invoices', 'edit invoices', 'add payments', 'add refunds',
            'view payment reports',
            'admit patients', 'discharge patients', 'assign beds',
            'view staff profiles',
            'view bed status', 'manage bed assignments',
            'generate patient reports', 'view dashboard analytics',
            'manage inquiries', 'manage contact messages', 'manage notice board',
            'use ai assistant', 'view analytics',
        ]);
        $receptionist->givePermissionTo($receptionistPermissions);

        // Pharmacist - Medicine management
        $pharmacist = Role::findByName('Pharmacist');
        $pharmacistPermissions = array_intersect($permissionNames, [
            'view patients',
            'view prescriptions', 'dispense medicines',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'generate expiry alerts', 'generate stock alerts',
            'view payment reports',
            'generate billing reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
        ]);
        $pharmacist->givePermissionTo($pharmacistPermissions);

        // Lab Technician - Laboratory operations
        $labTech = Role::findByName('Lab Technician');
        $labTechPermissions = array_intersect($permissionNames, [
            'view patients',
            'view prescriptions',
            'manage test categories', 'add test requests', 'enter test results', 'approve test results',
            'print lab reports', 'download lab reports',
            'generate pathology reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics', 'manage integrations',
        ]);
        $labTech->givePermissionTo($labTechPermissions);

        // Radiologist - Radiology operations
        $radiologist = Role::findByName('Radiologist');
        $radiologistPermissions = array_intersect($permissionNames, [
            'view patients',
            'view prescriptions',
            'manage test categories', 'add test requests', 'enter test results', 'approve test results',
            'print lab reports', 'download lab reports',
            'generate pathology reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics', 'manage integrations',
        ]);
        $radiologist->givePermissionTo($radiologistPermissions);

        // Accountant - Financial operations
        $accountant = Role::findByName('Accountant');
        $accountantPermissions = array_intersect($permissionNames, [
            'view patients',
            'view appointments',
            'view prescriptions',
            'create invoices', 'edit invoices', 'add payments', 'add refunds',
            'manage expenses', 'manage income', 'view payment reports', 'manage insurance claims',
            'manage packages',
            'view staff profiles', 'manage salaries', 'manage payrolls',
            'manage receipts', 'handle advance payments', 'view financial reports',
            'export ledgers', 'export journals',
            'generate billing reports', 'generate financial reports', 'export reports',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
        ]);
        $accountant->givePermissionTo($accountantPermissions);

        // Case Handler - Insurance and case management
        $caseHandler = Role::findByName('Case Handler');
        $caseHandlerPermissions = array_intersect($permissionNames, [
            'view patients', 'edit patients',
            'view appointments',
            'view prescriptions',
            'view payment reports', 'manage insurance claims',
            'admit patients', 'discharge patients',
            'view staff profiles',
            'generate patient reports', 'generate billing reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
        ]);
        $caseHandler->givePermissionTo($caseHandlerPermissions);

        // Ambulance Operator - Ambulance services
        $ambulanceOp = Role::findByName('Ambulance Operator');
        $ambulanceOpPermissions = array_intersect($permissionNames, [
            'view patients',
            'view appointments',
            'admit patients',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
        ]);
        $ambulanceOp->givePermissionTo($ambulanceOpPermissions);

        // HR Officer - Human resources
        $hrOfficer = Role::findByName('HR Officer');
        $hrOfficerPermissions = array_intersect($permissionNames, [
            'view patients',
            'manage staff profiles', 'assign departments', 'view attendance', 'manage salaries',
            'manage payrolls',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
        ]);
        $hrOfficer->givePermissionTo($hrOfficerPermissions);

        // Patient - Limited access to own data
        $patient = Role::findByName('Patient');
        $patientPermissions = array_intersect($permissionNames, [
            'view patients', 'edit patients',
            'create appointments', 'view appointments', 'cancel appointments',
            'view prescriptions',
            'view test results', 'download lab reports',
            'view payment reports',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
        ]);
        $patient->givePermissionTo($patientPermissions);

        // System Auditor - Read-only access
        $auditor = Role::findByName('System Auditor');
        $auditorPermissions = array_intersect($permissionNames, [
            'view patients', 'view appointments', 'view prescriptions',
            'view test results', 'view staff profiles', 'view bed status',
            'generate patient reports', 'generate billing reports', 'generate pathology reports',
            'generate operation reports', 'generate birth reports', 'generate death reports',
            'view dashboard analytics', 'view audit logs', 'view system logs',
            'view analytics',
        ]);
        $auditor->givePermissionTo($auditorPermissions);

        // Support Staff - Minimal access
        $supportStaff = Role::findByName('Support Staff');
        $supportStaffPermissions = array_intersect($permissionNames, [
            'view patients', 'view appointments', 'view staff profiles', 'view attendance',
            'view bed status', 'view dashboard analytics',
        ]);
        $supportStaff->givePermissionTo($supportStaffPermissions);

        // Telemedicine Doctor - Online consultations only
        $telemedicineDoctor = Role::findByName('Telemedicine Doctor');
        $telemedicineDoctorPermissions = array_intersect($permissionNames, [
            'view patients', 'edit patients',
            'create appointments', 'view appointments',
            'create prescriptions', 'view prescriptions',
            'view test results',
            'manage patient vitals', 'manage patient notes',
            'generate patient reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics', 'use telemedicine',
        ]);
        $telemedicineDoctor->givePermissionTo($telemedicineDoctorPermissions);

        // Inventory Manager - Stock management
        $inventoryManager = Role::findByName('Inventory Manager');
        $inventoryManagerPermissions = array_intersect($permissionNames, [
            'view patients',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'generate expiry alerts', 'generate stock alerts',
            'generate billing reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
        ]);
        $inventoryManager->givePermissionTo($inventoryManagerPermissions);

        // Procurement Officer - Purchasing
        $procurementOfficer = Role::findByName('Procurement Officer');
        $procurementOfficerPermissions = array_intersect($permissionNames, [
            'view patients',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'manage expenses',
            'view financial reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
        ]);
        $procurementOfficer->givePermissionTo($procurementOfficerPermissions);

        // IT Support - System maintenance
        $itSupport = Role::findByName('IT Support');
        $itSupportPermissions = array_intersect($permissionNames, [
            'view patients', 'view appointments', 'view prescriptions',
            'view test results', 'view staff profiles', 'view bed status',
            'view dashboard analytics', 'view audit logs', 'manage system settings',
            'manage backups', 'view system logs', 'manage api tokens', 'manage user accounts',
            'use ai assistant', 'view analytics', 'manage integrations',
        ]);
        $itSupport->givePermissionTo($itSupportPermissions);

        // Marketing Manager - CMS and communication
        $marketingManager = Role::findByName('Marketing Manager');
        $marketingManagerPermissions = array_intersect($permissionNames, [
            'view patients', 'view appointments',
            'manage homepage', 'manage services', 'manage doctors listing', 'send mass mails',
            'send mass sms', 'manage inquiries', 'manage contact messages', 'manage notice board',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'manage marketing', 'create marketing posts', 'edit marketing posts', 'delete marketing posts',
            'approve marketing posts', 'manage campaigns', 'manage social accounts', 'schedule posts',
            'manage comment replies', 'manage graphic assets', 'access marketing analytics', 'manage seo',
        ]);
        $marketingManager->givePermissionTo($marketingManagerPermissions);

        // System AI Bot - Automated operations
        $aiBot = Role::findByName('System AI Bot');
        $aiBotPermissions = array_intersect($permissionNames, [
            'view patients', 'view appointments', 'view prescriptions',
            'view test results', 'view staff profiles', 'view bed status',
            'send appointment reminders', 'generate expiry alerts', 'generate stock alerts',
            'use ai assistant', 'manage ai suggestions', 'view analytics', 'manage integrations',
            'use telemedicine', 'manage rfid tags', 'monitor iot sensors',
            'view audit logs', 'view system logs',
        ]);
        $aiBot->givePermissionTo($aiBotPermissions);
    }
}
