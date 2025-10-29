<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SidebarPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Additional permissions that might be missing for sidebar functionality
        $additionalPermissions = [
            'view dashboard',
            'view doctors',
            'view medicines',
            'view lab tests',
            'view payrolls',
            'view reports',
            'manage settings',
            'view billing',
            'view appointments',
            'view patients',
            'view prescriptions',
            'view invoices',
            'view payments',
            'view staff profiles',
            'view attendance',
            'view bed status',
            'view test results',
            'view financial reports',
            'view audit logs',
            'view system logs',
            'manage backups',
            'manage api tokens',
            'manage user accounts',
            'manage integrations',
            'manage ai suggestions',
            'manage rfid tags',
            'monitor iot sensors',
            'use telemedicine',
            'view analytics',
            'use ai assistant',
            'manage roles',
            'manage permissions',
            'manage system settings',
            'manage hospital info',
            'manage notification templates',
            'manage homepage',
            'manage services',
            'manage doctors listing',
            'send mass mails',
            'send mass sms',
            'manage inquiries',
            'manage contact messages',
            'manage notice board',
            'manage hospital branches',
            'assign staff per branch',
            'view branch analytics',
            'manage blood bank',
            'manage blood inventory',
            'manage blood requests',
            'manage ambulances',
            'manage emergency admissions',
            'manage ambulance calls',
            'manage insurance providers',
            'manage patient insurance',
            'manage insurance claims',
            'manage documents',
            'manage document types',
            'manage advance payments',
            'manage expense categories',
            'manage expenses',
            'manage packages',
            'manage package items',
            'manage nurses',
            'manage nurse departments',
            'manage case handlers',
            'manage patient cases',
            'manage birth reports',
            'manage death reports',
            'manage operation reports',
            'manage diagnosis categories',
            'manage patient diagnoses',
            'manage receptionists',
            'manage pharmacists',
            'manage lab technicians',
            'manage accountants',
            'manage blog posts',
            'manage blog categories',
            'manage gallery items',
            'manage gallery categories',
            'manage job postings',
            'manage job applications',
            'manage testimonials',
            'manage queue',
            'manage visitor logs',
            'manage AI features',
            'manage API tokens',
            'manage mobile app',
            'manage voice notes',
            'manage lab equipment',
            'manage insurance API',
            'manage BI analytics',
            'manage telemedicine sessions',
            'manage patient portal accounts',
            'manage RFID tags',
            'manage IoT sensors',
            'manage API integration',
            'manage mobile integration',
            'manage voice integration',
            'manage lab integration',
            'manage insurance integration',
            'manage analytics integration',
            'manage telemedicine integration',
            'manage portal integration',
            'manage RFID integration',
            'manage IoT integration',
            'manage API management',
            'manage mobile management',
            'manage voice management',
            'manage lab management',
            'manage insurance management',
            'manage analytics management',
            'manage telemedicine management',
            'manage portal management',
            'manage RFID management',
            'manage IoT management'
        ];

        // Create permissions that don't exist
        foreach ($additionalPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        echo "Sidebar permissions seeded successfully!\n";
    }
}
