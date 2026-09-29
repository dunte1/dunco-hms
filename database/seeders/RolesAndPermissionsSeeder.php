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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->createPermissions();
        $this->createRoles();
        $this->assignPermissionsToRoles();
    }

    private function createPermissions(): void
    {
        $permissions = [
            // ── Patient Management ──
            'view patients', 'add patients', 'edit patients', 'delete patients',
            'manage admissions', 'manage discharges', 'assign beds', 'upload documents',
            'merge patients', 'emergency register patients', 'manage patient biometrics',

            // ── Appointments & Scheduling ──
            'create appointments', 'manage appointments', 'reschedule appointments', 'cancel appointments',
            'view doctor schedules', 'send appointment reminders',

            // ── Triage ──
            'manage triage records', 'manage triage escalations', 'view triage queue',

            // ── Consultation ──
            'create consultations', 'manage consultations', 'diagnose patients',
            'manage clinical notes', 'manage procedure orders', 'issue sick notes', 'issue medical certificates',

            // ── Emergency ──
            'manage emergency visits', 'manage resuscitation', 'manage trauma assessments',
            'manage observation stays', 'set emergency disposition', 'defer emergency billing',

            // ── Inpatient / Ward ──
            'manage ward rounds', 'manage nursing notes', 'manage fluid balance',
            'manage medication administration', 'manage diet orders', 'sign discharge summaries', 'manage inpatient transfers',

            // ── ICU/HDU ──
            'manage icu admissions', 'manage critical care charts', 'manage ventilators',
            'record abg results', 'manage sedation scores', 'manage infusions',

            // ── Theatre ──
            'manage theatre bookings', 'manage surgical waiting list', 'record preop assessments',
            'complete who checklists', 'manage theatre teams', 'manage theatre consumables',
            'manage theatre specimens', 'manage recovery records',

            // ── Anaesthesia ──
            'manage anaesthesia assessments', 'manage anaesthesia records', 'manage anaesthesia drugs',
            'record intraop vitals', 'manage anaesthesia complications', 'record post anaesthesia reviews',

            // ── Nursing ──
            'manage nurse allocations', 'manage duty rosters', 'manage shift handovers',
            'manage nursing procedures',

            // ── Prescriptions & Medicines ──
            'create prescriptions', 'edit prescriptions', 'view prescriptions', 'dispense medicines',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'generate expiry alerts', 'generate stock alerts', 'manage controlled drugs',
            'process drug returns', 'manage goods received notes', 'verify dispensations',

            // ── Lab & Radiology ──
            'manage test categories', 'add test requests', 'enter test results', 'approve test results',
            'print lab reports', 'download lab reports', 'view test results',
            'manage lab specimens', 'manage lab worklists', 'verify lab results',
            'manage radiology schedules', 'manage radiology worklist', 'approve radiology reports',
            'record contrast administration',

            // ── Blood Bank ──
            'manage blood bank', 'manage blood donations', 'manage blood units',
            'perform crossmatch', 'issue blood units', 'manage transfusions', 'report transfusion reactions',

            // ── Billing & Finance ──
            'create invoices', 'edit invoices', 'add payments', 'add refunds',
            'manage charges', 'manage discounts', 'manage waivers', 'manage cashier sessions',
            'manage expenses', 'manage income', 'view payment reports', 'manage insurance claims',
            'manage packages',

            // ── SHA / Insurance ──
            'verify insurance', 'manage preauthorizations', 'manage claim batches',
            'manage claim rejections', 'manage claim remittances', 'manage tariffs', 'manage benefit packages',

            // ── Finance / Accounting ──
            'manage journal entries', 'post journal entries', 'reverse journal entries',
            'manage fiscal periods', 'manage bank accounts', 'reconcile bank accounts',
            'manage budgets', 'view financial reports', 'export ledgers', 'export journals',

            // ── IPD/OPD ──
            'admit patients', 'manage patient vitals', 'manage patient notes',
            'assign doctors', 'assign nurses', 'update bed status',

            // ── Maternity ──
            'manage anc registrations', 'manage pregnancies', 'manage labour records',
            'manage partograph entries', 'record deliveries', 'manage postnatal visits',
            'manage family planning visits',

            // ── Neonatal ──
            'manage newborns', 'manage nicu admissions', 'manage incubator assignments',
            'manage phototherapy sessions', 'manage neonatal feeds', 'record neonatal assessments',

            // ── Paediatrics ──
            'manage growth measurements', 'manage developmental assessments',
            'manage immunization schedules', 'manage child protection cases',

            // ── HIV ──
            'manage hts encounters', 'manage hiv care enrollments', 'manage art regimens',
            'manage viral load results', 'manage pep prep records', 'manage partner notifications',
            'manage hei records',

            // ── TB ──
            'manage tb screenings', 'manage tb cases', 'manage tb treatments',
            'manage tb adherence logs', 'manage tb contacts',

            // ── Oncology ──
            'manage cancer registrations', 'manage oncology treatment plans',
            'manage chemo protocols', 'manage chemo cycles', 'manage chemo infusions',
            'manage adverse events',

            // ── Public Health ──
            'manage immunization schedules', 'manage family planning visits',
            'manage surveillance cases', 'manage notifiable disease reports', 'manage outbreak events',

            // ── Allied (Dental, Ophthalmology, ENT, Rehab, Nutrition) ──
            'manage dental records', 'manage eye examinations', 'manage ent records',
            'manage rehab sessions', 'manage nutrition records',

            // ── Mental Health ──
            'manage mh assessments', 'manage mh treatment plans', 'manage counselling sessions',

            // ── Social Work ──
            'manage social assessments', 'manage welfare waivers', 'manage discharge plans',

            // ── Mortuary ──
            'manage mortuary records', 'manage mortuary slots', 'manage body identifications',
            'manage postmortems', 'issue death certificates',

            // ── Ambulance ──
            'manage ambulances', 'manage ambulance crews', 'manage ambulance trips',
            'manage ambulance fuel', 'manage ambulance maintenance', 'record patient handovers',

            // ── CSSD ──
            'manage cssd instruments', 'manage cssd cycles', 'manage sterilizer runs',
            'record sterility indicators', 'manage cssd issues', 'manage cssd returns',

            // ── IPC ──
            'manage hai surveillance', 'manage isolation orders', 'record hand hygiene observations',
            'manage ipc audits', 'manage outbreak investigations', 'record antibiotic usage',

            // ── Maintenance ──
            'manage maintenance requests', 'manage work orders', 'manage calibrations',
            'manage assets', 'transfer assets', 'dispose assets',

            // ── Linen / Kitchen ──
            'manage linen records', 'manage laundry batches', 'manage meal orders',
            'manage kitchen inventory',

            // ── Security ──
            'manage security incidents', 'manage lost found items', 'manage access events',
            'manage vehicle access', 'manage visitor passes',

            // ── Fire / Safety ──
            'manage safety incidents', 'manage fire equipment', 'record fire inspections',
            'record emergency drills', 'manage risk assessments',

            // ── Medical Records ──
            'manage record requests', 'upload scanned documents', 'manage icd coding',
            'manage khis reports', 'manage data quality issues',

            // ── Doctors & Staff ──
            'manage staff profiles', 'assign departments', 'view attendance', 'manage salaries',
            'manage payrolls', 'view doctors', 'manage nurses', 'manage case handlers',

            // ── Bed & Room Management ──
            'create bed types', 'edit bed types', 'view bed status', 'manage bed assignments',

            // ── Credentialing ──
            'manage practitioner qualifications', 'manage practitioner licences',
            'manage privileges', 'manage oncall schedules', 'manage cme records',

            // ── HR ──
            'manage employee contracts', 'manage disciplinary records', 'manage staff licences',
            'manage payroll exports', 'manage staff documents',

            // ── Appointments / Queue ──
            'manage schedule slots', 'manage appointment reminders',

            // ── Referrals ──
            'manage referrals', 'manage referring facilities', 'manage referral documents',
            'manage referral feedback',

            // ── Telemedicine ──
            'use telemedicine', 'manage tele participants',

            // ── Patient Portal ──
            'manage portal dependants', 'send portal messages', 'view portal access logs',

            // ── Communication ──
            'send outbound messages', 'manage message campaigns', 'manage message opt outs',

            // ── Research ──
            'manage trainees', 'manage trainee assessments', 'manage research projects',
            'manage ethics approvals', 'manage publications',

            // ── Quality ──
            'manage complaints', 'manage incidents', 'manage mortality reviews',
            'manage quality indicators', 'manage indicator values', 'manage corrective actions',

            // ── Audit / Compliance ──
            'view audit logs', 'manage break glass events', 'manage compliance checklists',
            'manage compliance responses', 'manage data subject requests',

            // ── ICT ──
            'manage it assets', 'manage it tickets', 'manage backups', 'manage software licences',

            // ── Dashboard ──
            'manage dashboard definitions', 'manage kpi definitions', 'record kpi snapshots',
            'manage saved reports', 'manage report schedules',

            // ── Config ──
            'manage hospital info', 'manage notification templates', 'manage roles', 'manage permissions',
            'manage system settings', 'manage user accounts', 'manage integrations', 'manage feature flags',
            'manage number sequences', 'manage services', 'manage price lists', 'manage tax rates',
            'manage payment methods', 'manage document templates',

            // ── Reports ──
            'generate patient reports', 'generate billing reports', 'generate pathology reports',
            'generate operation reports', 'generate birth reports', 'generate death reports',
            'generate financial reports', 'export reports', 'view dashboard analytics',

            // ── CMS & Communication ──
            'manage homepage', 'manage services listing', 'manage doctors listing', 'send mass mails',
            'send mass sms', 'manage inquiries', 'manage contact messages', 'manage notice board',

            // ── Multi-Hospital / Tenancy ──
            'manage hospital branches', 'assign staff per branch', 'view branch analytics',

            // ── AI & Advanced Features ──
            'use ai assistant', 'manage ai suggestions', 'view analytics',
            'manage rfid tags', 'monitor iot sensors',

            // ── Marketing Suite ──
            'manage marketing', 'create marketing posts', 'edit marketing posts', 'delete marketing posts',
            'approve marketing posts', 'manage campaigns', 'manage social accounts', 'schedule posts',
            'manage comment replies', 'manage graphic assets', 'access marketing analytics', 'manage seo',

            // ── IT Tickets (cross-cutting — all users can raise) ──
            'raise it tickets',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $this->command->info('[OK] Seeded ' . count($permissions) . ' permissions.');
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
            // New specialized roles
            'Maternity Nurse',
            'ICU Nurse',
            'Theatre Nurse',
            'CSSD Technician',
            'Mortuary Attendant',
            'Security Officer',
            'Quality Officer',
            'Biomedical Engineer',
            'Dietitian',
            'Social Worker',
            'Mental Health Professional',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $this->command->info('[OK] Seeded ' . count($roles) . ' roles.');
    }

    private function assignPermissionsToRoles(): void
    {
        $allPermissions = Permission::all();
        $p = $allPermissions->pluck('name')->toArray();
        $has = fn(array $subset) => array_intersect($p, $subset);

        // Super Admin — everything
        Role::findByName('Super Admin')->givePermissionTo($allPermissions);

        // Hospital Admin
        Role::findByName('Hospital Admin')->givePermissionTo($has([
            'view patients', 'add patients', 'edit patients', 'delete patients',
            'manage admissions', 'manage discharges', 'assign beds', 'upload documents',
            'merge patients', 'emergency register patients',
            'create appointments', 'manage appointments', 'reschedule appointments', 'cancel appointments',
            'view doctor schedules', 'send appointment reminders',
            'manage triage records', 'manage triage escalations', 'view triage queue',
            'create consultations', 'manage consultations', 'diagnose patients',
            'manage clinical notes', 'manage procedure orders', 'issue sick notes', 'issue medical certificates',
            'manage emergency visits', 'manage resuscitation', 'manage trauma assessments',
            'manage observation stays', 'set emergency disposition', 'defer emergency billing',
            'manage ward rounds', 'manage nursing notes', 'manage fluid balance',
            'manage medication administration', 'manage diet orders', 'sign discharge summaries', 'manage inpatient transfers',
            'manage icu admissions', 'manage critical care charts', 'manage ventilators',
            'manage theatre bookings', 'manage surgical waiting list', 'record preop assessments',
            'complete who checklists', 'manage theatre teams', 'manage theatre consumables',
            'manage anaesthesia assessments', 'manage anaesthesia records',
            'manage nurse allocations', 'manage duty rosters', 'manage shift handovers',
            'create prescriptions', 'edit prescriptions', 'view prescriptions', 'dispense medicines',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'generate expiry alerts', 'generate stock alerts', 'manage controlled drugs',
            'manage test categories', 'add test requests', 'enter test results', 'approve test results',
            'print lab reports', 'download lab reports', 'view test results',
            'manage lab specimens', 'manage lab worklists', 'verify lab results',
            'manage radiology schedules', 'manage radiology worklist', 'approve radiology reports',
            'manage blood bank', 'manage blood donations', 'manage blood units', 'perform crossmatch',
            'create invoices', 'edit invoices', 'add payments', 'add refunds',
            'manage charges', 'manage discounts', 'manage waivers', 'manage cashier sessions',
            'manage expenses', 'manage income', 'view payment reports', 'manage insurance claims',
            'manage claim batches', 'manage claim rejections', 'manage claim remittances', 'manage tariffs',
            'manage journal entries', 'post journal entries', 'view financial reports',
            'manage anc registrations', 'manage pregnancies', 'manage labour records', 'record deliveries',
            'manage postnatal visits', 'manage family planning visits',
            'manage newborns', 'manage nicu admissions',
            'manage growth measurements', 'manage developmental assessments', 'manage immunization schedules',
            'manage hts encounters', 'manage hiv care enrollments', 'manage art regimens',
            'manage tb cases', 'manage tb treatments',
            'manage cancer registrations', 'manage oncology treatment plans',
            'manage mh assessments', 'manage mh treatment plans',
            'manage social assessments', 'manage welfare waivers',
            'manage mortuary records', 'manage postmortems', 'issue death certificates',
            'manage ambulance trips', 'manage ambulance crews',
            'manage cssd instruments', 'manage cssd cycles',
            'manage hai surveillance', 'manage isolation orders', 'manage ipc audits',
            'manage maintenance requests', 'manage work orders', 'manage calibrations', 'manage assets',
            'manage linen records', 'manage laundry batches', 'manage meal orders',
            'manage security incidents', 'manage visitor passes',
            'manage safety incidents', 'manage fire equipment', 'manage risk assessments',
            'manage record requests', 'upload scanned documents', 'manage icd coding', 'manage khis reports',
            'manage staff profiles', 'assign departments', 'view attendance', 'manage salaries', 'manage payrolls',
            'manage employee contracts', 'manage disciplinary records', 'manage staff licences',
            'manage referrals', 'manage referring facilities',
            'use telemedicine', 'manage complaints', 'manage incidents', 'manage mortality reviews',
            'manage quality indicators', 'manage corrective actions',
            'view audit logs', 'manage break glass events', 'manage compliance checklists',
            'manage it assets', 'manage it tickets', 'manage backups',
            'manage dashboard definitions', 'manage kpi definitions', 'manage saved reports', 'manage report schedules',
            'manage hospital info', 'manage notification templates', 'manage roles', 'manage permissions',
            'manage system settings', 'manage user accounts', 'manage integrations',
            'manage number sequences', 'manage services', 'manage price lists', 'manage payment methods',
            'generate patient reports', 'generate billing reports', 'generate pathology reports',
            'generate operation reports', 'generate birth reports', 'generate death reports',
            'generate financial reports', 'export reports', 'view dashboard analytics',
            'manage homepage', 'send mass mails', 'send mass sms', 'manage inquiries', 'manage notice board',
            'manage hospital branches', 'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // Doctor
        Role::findByName('Doctor')->givePermissionTo($has([
            'view patients', 'edit patients', 'upload documents',
            'create appointments', 'manage appointments', 'view doctor schedules',
            'manage triage records', 'view triage queue',
            'create consultations', 'manage consultations', 'diagnose patients',
            'manage clinical notes', 'manage procedure orders', 'issue sick notes', 'issue medical certificates',
            'manage emergency visits', 'manage resuscitation', 'manage trauma assessments',
            'manage ward rounds', 'manage nursing notes',
            'manage icu admissions', 'manage critical care charts',
            'manage theatre bookings', 'record preop assessments', 'complete who checklists',
            'manage theatre teams', 'manage theatre specimens',
            'manage anaesthesia assessments', 'manage anaesthesia records',
            'create prescriptions', 'edit prescriptions', 'view prescriptions',
            'add test requests', 'view test results', 'print lab reports', 'download lab reports',
            'manage lab specimens', 'verify lab results',
            'manage blood bank', 'perform crossmatch',
            'view payment reports', 'manage insurance claims',
            'manage anc registrations', 'manage pregnancies', 'manage labour records', 'record deliveries',
            'manage postnatal visits', 'manage family planning visits',
            'manage newborns', 'manage nicu admissions',
            'manage growth measurements', 'manage developmental assessments',
            'manage hts encounters', 'manage hiv care enrollments', 'manage art regimens',
            'manage tb cases', 'manage tb treatments',
            'manage cancer registrations', 'manage oncology treatment plans',
            'manage mh assessments', 'manage mh treatment plans',
            'manage social assessments',
            'manage postmortems', 'issue death certificates',
            'manage record requests', 'manage icd coding',
            'admit patients', 'manage patient vitals', 'manage patient notes', 'assign nurses',
            'manage practitioner qualifications', 'manage practitioner licences',
            'manage privileges', 'manage oncall schedules', 'manage cme records',
            'manage referrals',
            'use telemedicine', 'manage mortality reviews',
            'generate patient reports', 'generate pathology reports', 'generate operation reports',
            'view dashboard analytics', 'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // Nurse
        Role::findByName('Nurse')->givePermissionTo($has([
            'view patients', 'edit patients', 'upload documents',
            'view appointments', 'view doctor schedules',
            'manage triage records', 'manage triage escalations', 'view triage queue',
            'create consultations', 'manage clinical notes',
            'manage ward rounds', 'manage nursing notes', 'manage fluid balance',
            'manage medication administration', 'manage diet orders',
            'manage nurse allocations', 'manage duty rosters', 'manage shift handovers', 'manage nursing procedures',
            'manage icu admissions', 'manage critical care charts', 'manage sedation scores', 'manage infusions',
            'manage theatre teams', 'manage recovery records',
            'view prescriptions', 'manage lab specimens',
            'view payment reports',
            'admit patients', 'manage patient vitals', 'manage patient notes', 'update bed status',
            'manage anc registrations', 'manage pregnancies', 'manage labour records', 'record deliveries',
            'manage postnatal visits', 'manage family planning visits',
            'manage newborns', 'manage nicu admissions', 'manage neonatal feeds', 'record neonatal assessments',
            'manage growth measurements', 'manage immunization schedules',
            'manage mh assessments', 'manage counselling sessions',
            'manage Hai surveillance', 'manage isolation orders', 'record hand hygiene observations',
            'manage linen records',
            'view bed status', 'manage bed assignments',
            'view staff profiles', 'view attendance',
            'generate patient reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // Receptionist
        Role::findByName('Receptionist')->givePermissionTo($has([
            'view patients', 'add patients', 'edit patients', 'upload documents',
            'emergency register patients',
            'create appointments', 'manage appointments', 'reschedule appointments', 'cancel appointments',
            'view doctor schedules', 'send appointment reminders',
            'manage triage queue',
            'view prescriptions', 'view test results', 'print lab reports', 'download lab reports',
            'create invoices', 'edit invoices', 'add payments', 'add refunds',
            'manage charges', 'view payment reports',
            'admit patients', 'manage discharges', 'assign beds',
            'manage referrals', 'manage referring facilities',
            'view bed status', 'manage bed assignments',
            'view staff profiles',
            'generate patient reports', 'view dashboard analytics',
            'manage inquiries', 'manage contact messages', 'manage notice board',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // Pharmacist
        Role::findByName('Pharmacist')->givePermissionTo($has([
            'view patients',
            'view prescriptions', 'dispense medicines', 'verify dispensations',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'manage controlled drugs', 'process drug returns', 'manage goods received notes',
            'generate expiry alerts', 'generate stock alerts',
            'view payment reports',
            'generate billing reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // Lab Technician
        Role::findByName('Lab Technician')->givePermissionTo($has([
            'view patients',
            'view prescriptions',
            'manage test categories', 'add test requests', 'enter test results', 'approve test results',
            'print lab reports', 'download lab reports',
            'manage lab specimens', 'manage lab worklists', 'verify lab results',
            'generate pathology reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics', 'manage integrations',
            'raise it tickets',
        ]));

        // Radiologist
        Role::findByName('Radiologist')->givePermissionTo($has([
            'view patients',
            'view prescriptions',
            'manage test categories', 'add test requests', 'enter test results', 'approve test results',
            'print lab reports', 'download lab reports',
            'manage radiology schedules', 'manage radiology worklist', 'approve radiology reports',
            'record contrast administration',
            'generate pathology reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics', 'manage integrations',
            'raise it tickets',
        ]));

        // Accountant
        Role::findByName('Accountant')->givePermissionTo($has([
            'view patients', 'view appointments', 'view prescriptions',
            'create invoices', 'edit invoices', 'add payments', 'add refunds',
            'manage charges', 'manage discounts', 'manage waivers', 'manage cashier sessions',
            'manage expenses', 'manage income', 'view payment reports', 'manage insurance claims',
            'manage claim batches', 'manage claim rejections', 'manage claim remittances', 'manage tariffs',
            'manage journal entries', 'post journal entries', 'reverse journal entries',
            'manage fiscal periods', 'manage bank accounts', 'reconcile bank accounts',
            'manage packages',
            'view staff profiles', 'manage salaries', 'manage payrolls',
            'view financial reports', 'export ledgers', 'export journals',
            'generate billing reports', 'generate financial reports', 'export reports',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // Case Handler
        Role::findByName('Case Handler')->givePermissionTo($has([
            'view patients', 'edit patients',
            'view appointments',
            'view prescriptions',
            'view payment reports', 'manage insurance claims',
            'manage preauthorizations', 'manage claim batches', 'manage claim rejections',
            'admit patients', 'manage discharges',
            'manage referrals',
            'view staff profiles',
            'generate patient reports', 'generate billing reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // Ambulance Operator
        Role::findByName('Ambulance Operator')->givePermissionTo($has([
            'view patients',
            'view appointments',
            'manage ambulances', 'manage ambulance crews', 'manage ambulance trips',
            'manage ambulance fuel', 'manage ambulance maintenance', 'record patient handovers',
            'admit patients',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // HR Officer
        Role::findByName('HR Officer')->givePermissionTo($has([
            'view patients',
            'manage staff profiles', 'assign departments', 'view attendance', 'manage salaries', 'manage payrolls',
            'manage employee contracts', 'manage disciplinary records', 'manage staff licences',
            'manage payroll exports', 'manage staff documents',
            'manage duty rosters',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // Patient
        Role::findByName('Patient')->givePermissionTo($has([
            'view patients', 'edit patients',
            'create appointments', 'view appointments', 'cancel appointments',
            'view prescriptions',
            'view test results', 'download lab reports',
            'view payment reports',
            'manage portal dependants', 'send portal messages',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // System Auditor
        Role::findByName('System Auditor')->givePermissionTo($has([
            'view patients', 'view appointments', 'view prescriptions',
            'view test results', 'view staff profiles', 'view bed status',
            'generate patient reports', 'generate billing reports', 'generate pathology reports',
            'generate operation reports', 'generate birth reports', 'generate death reports',
            'view dashboard analytics', 'view audit logs', 'view system logs',
            'manage break glass events', 'manage compliance checklists', 'manage compliance responses',
            'view analytics',
            'raise it tickets',
        ]));

        // Support Staff
        Role::findByName('Support Staff')->givePermissionTo($has([
            'view patients', 'view appointments', 'view staff profiles', 'view attendance',
            'view bed status', 'view dashboard analytics',
            'manage linen records', 'manage laundry batches',
            'manage meal orders', 'manage kitchen inventory',
            'manage safety incidents', 'manage fire equipment',
            'raise it tickets',
        ]));

        // Telemedicine Doctor
        Role::findByName('Telemedicine Doctor')->givePermissionTo($has([
            'view patients', 'edit patients',
            'create appointments', 'view appointments',
            'create prescriptions', 'view prescriptions',
            'view test results',
            'manage patient vitals', 'manage patient notes',
            'use telemedicine', 'manage tele participants',
            'generate patient reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // Inventory Manager
        Role::findByName('Inventory Manager')->givePermissionTo($has([
            'view patients',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'manage controlled drugs', 'process drug returns', 'manage goods received notes',
            'generate expiry alerts', 'generate stock alerts',
            'manage cssd instruments', 'manage cssd cycles', 'manage sterilizer runs',
            'generate billing reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // Procurement Officer
        Role::findByName('Procurement Officer')->givePermissionTo($has([
            'view patients',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'manage goods received notes',
            'manage expenses',
            'manage staff profiles',
            'view financial reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        // IT Support
        Role::findByName('IT Support')->givePermissionTo($has([
            'view patients', 'view appointments', 'view prescriptions',
            'view test results', 'view staff profiles', 'view bed status',
            'view dashboard analytics', 'view audit logs', 'manage system settings',
            'manage backups', 'view system logs', 'manage api tokens', 'manage user accounts',
            'manage it assets', 'manage it tickets', 'manage software licences',
            'use ai assistant', 'view analytics', 'manage integrations',
            'raise it tickets',
        ]));

        // Marketing Manager
        Role::findByName('Marketing Manager')->givePermissionTo($has([
            'view patients', 'view appointments',
            'manage homepage', 'manage services listing', 'manage doctors listing', 'send mass mails',
            'send mass sms', 'manage inquiries', 'manage contact messages', 'manage notice board',
            'manage outbound messages', 'manage message campaigns',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'manage marketing', 'create marketing posts', 'edit marketing posts', 'delete marketing posts',
            'approve marketing posts', 'manage campaigns', 'manage social accounts', 'schedule posts',
            'manage comment replies', 'manage graphic assets', 'access marketing analytics', 'manage seo',
            'raise it tickets',
        ]));

        // System AI Bot
        Role::findByName('System AI Bot')->givePermissionTo($has([
            'view patients', 'view appointments', 'view prescriptions',
            'view test results', 'view staff profiles', 'view bed status',
            'send appointment reminders', 'generate expiry alerts', 'generate stock alerts',
            'use ai assistant', 'manage ai suggestions', 'view analytics', 'manage integrations',
            'use telemedicine', 'manage rfid tags', 'monitor iot sensors',
            'view audit logs', 'view system logs',
        ]));

        // ── New specialized roles ──

        // Maternity Nurse
        Role::findByName('Maternity Nurse')->givePermissionTo($has([
            'view patients', 'edit patients', 'upload documents',
            'manage triage records', 'view triage queue',
            'manage ward rounds', 'manage nursing notes', 'manage fluid balance',
            'manage medication administration', 'manage diet orders',
            'manage nurse allocations', 'manage duty rosters', 'manage shift handovers',
            'manage anc registrations', 'manage pregnancies', 'manage labour records',
            'manage partograph entries', 'record deliveries', 'manage postnatal visits', 'manage family planning visits',
            'manage newborns', 'manage neonatal feeds', 'record neonatal assessments',
            'view prescriptions', 'manage lab specimens',
            'admit patients', 'manage patient vitals', 'manage patient notes',
            'generate patient reports', 'view dashboard analytics',
            'raise it tickets',
        ]));

        // ICU Nurse
        Role::findByName('ICU Nurse')->givePermissionTo($has([
            'view patients', 'edit patients', 'upload documents',
            'manage ward rounds', 'manage nursing notes', 'manage fluid balance',
            'manage medication administration', 'manage diet orders',
            'manage nurse allocations', 'manage duty rosters', 'manage shift handovers',
            'manage icu admissions', 'manage critical care charts', 'manage ventilators',
            'record abg results', 'manage sedation scores', 'manage infusions',
            'manage theatre teams', 'manage recovery records',
            'view prescriptions', 'manage lab specimens',
            'admit patients', 'manage patient vitals', 'manage patient notes',
            'generate patient reports', 'view dashboard analytics',
            'raise it tickets',
        ]));

        // Theatre Nurse
        Role::findByName('Theatre Nurse')->givePermissionTo($has([
            'view patients', 'edit patients', 'upload documents',
            'manage theatre bookings', 'manage surgical waiting list', 'record preop assessments',
            'complete who checklists', 'manage theatre teams', 'manage theatre consumables',
            'manage theatre specimens', 'manage recovery records',
            'manage anaesthesia assessments', 'manage anaesthesia records', 'manage anaesthesia drugs',
            'record intraop vitals', 'manage anaesthesia complications', 'record post anaesthesia reviews',
            'manage cssd instruments', 'manage cssd issues', 'manage cssd returns',
            'view prescriptions',
            'admit patients', 'manage patient vitals', 'manage patient notes',
            'generate patient reports', 'view dashboard analytics',
            'raise it tickets',
        ]));

        // CSSD Technician
        Role::findByName('CSSD Technician')->givePermissionTo($has([
            'view patients',
            'manage cssd instruments', 'manage cssd cycles', 'manage sterilizer runs',
            'record sterility indicators', 'manage cssd issues', 'manage cssd returns',
            'view dashboard analytics',
            'raise it tickets',
        ]));

        // Mortuary Attendant
        Role::findByName('Mortuary Attendant')->givePermissionTo($has([
            'view patients',
            'manage mortuary records', 'manage mortuary slots', 'manage body identifications',
            'manage postmortems', 'issue death certificates',
            'view dashboard analytics',
            'raise it tickets',
        ]));

        // Security Officer
        Role::findByName('Security Officer')->givePermissionTo($has([
            'view patients',
            'manage security incidents', 'manage lost found items', 'manage access events',
            'manage vehicle access', 'manage visitor passes',
            'manage safety incidents', 'record emergency drills',
            'view dashboard analytics',
            'raise it tickets',
        ]));

        // Quality Officer
        Role::findByName('Quality Officer')->givePermissionTo($has([
            'view patients', 'view appointments',
            'manage complaints', 'manage incidents', 'manage mortality reviews',
            'manage quality indicators', 'manage indicator values', 'manage corrective actions',
            'manage record requests', 'manage icd coding', 'manage khis reports', 'manage data quality issues',
            'generate patient reports', 'generate billing reports',
            'view dashboard analytics', 'view audit logs',
            'raise it tickets',
        ]));

        // Biomedical Engineer
        Role::findByName('Biomedical Engineer')->givePermissionTo($has([
            'view patients',
            'manage maintenance requests', 'manage work orders', 'manage calibrations',
            'manage assets', 'transfer assets', 'dispose assets',
            'manage it assets',
            'view dashboard analytics',
            'raise it tickets',
        ]));

        // Dietitian
        Role::findByName('Dietitian')->givePermissionTo($has([
            'view patients',
            'manage nutrition records', 'manage diet orders',
            'manage meal orders',
            'generate patient reports', 'view dashboard analytics',
            'raise it tickets',
        ]));

        // Social Worker
        Role::findByName('Social Worker')->givePermissionTo($has([
            'view patients',
            'manage social assessments', 'manage welfare waivers', 'manage discharge plans',
            'manage mh assessments', 'manage counselling sessions',
            'generate patient reports', 'view dashboard analytics',
            'raise it tickets',
        ]));

        // Mental Health Professional
        Role::findByName('Mental Health Professional')->givePermissionTo($has([
            'view patients', 'edit patients',
            'create consultations', 'manage consultations', 'diagnose patients',
            'manage clinical notes',
            'manage mh assessments', 'manage mh treatment plans', 'manage counselling sessions',
            'create prescriptions', 'view prescriptions',
            'generate patient reports', 'view dashboard analytics',
            'raise it tickets',
        ]));

        $this->command->info('[OK] Assigned permissions to all roles.');
    }
}
