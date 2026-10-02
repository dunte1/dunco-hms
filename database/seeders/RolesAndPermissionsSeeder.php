<?php

namespace Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = $this->permissionList();
        $roles = $this->roleList();

        $now = now();

        // Bulk insert permissions (ignore duplicates)
        $permissionRows = [];
        foreach (array_values(array_unique($permissions)) as $name) {
            $permissionRows[] = [
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        Permission::insertOrIgnore($permissionRows);

        // Bulk insert roles
        $roleRows = [];
        foreach ($roles as $name) {
            $roleRows[] = [
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        Role::insertOrIgnore($roleRows);

        $this->assignPermissionsToRoles();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('[OK] Seeded ' . count($permissionRows) . ' permissions and ' . count($roleRows) . ' roles.');
    }

    /**
     * @return array<int, string>
     */
    private function permissionList(): array
    {
        return [
            // ── Patient Management ──
            'view patients', 'add patients', 'edit patients', 'delete patients',
            'merge patients', 'emergency register patients', 'manage patient biometrics',
            'manage admissions', 'manage discharges', 'assign beds', 'update bed status',
            'view bed status', 'create bed types', 'edit bed types',
            'manage bed assignments', 'upload documents', 'manage patient diagnoses',
            'manage patient cases', 'manage case handlers', 'manage patient insurance',
            'manage referral documents', 'manage patient portal accounts',

            // ── Appointments ──
            'create appointments', 'manage appointments', 'reschedule appointments',
            'cancel appointments', 'view appointments', 'view doctor schedules',
            'send appointment reminders',

            // ── Triage ──
            'manage triage records', 'manage triage escalations', 'view triage queue',
            'manage triage queue',

            // ── Consultation ──
            'create consultations', 'manage consultations', 'diagnose patients',
            'manage clinical notes', 'manage procedure orders', 'issue sick notes',
            'issue medical certificates',

            // ── Emergency ──
            'manage emergency visits', 'manage emergency admissions', 'manage resuscitation',
            'manage trauma assessments', 'manage observation stays', 'set emergency disposition',
            'defer emergency billing',

            // ── Inpatient / Ward ──
            'manage ward rounds', 'manage nursing notes', 'manage fluid balance',
            'manage medication administration', 'manage diet orders', 'sign discharge summaries',
            'manage inpatient transfers', 'record patient handovers',

            // ── ICU/HDU ──
            'manage icu admissions', 'manage critical care charts', 'manage ventilators',
            'manage sedation scores', 'manage infusions', 'record abg results',

            // ── Theatre ──
            'manage theatre bookings', 'manage surgical waiting list', 'record preop assessments',
            'complete who checklists', 'manage theatre teams', 'manage theatre consumables',
            'manage theatre specimens', 'manage recovery records', 'generate operation reports',

            // ── Anaesthesia ──
            'manage anaesthesia assessments', 'manage anaesthesia records', 'manage anaesthesia drugs',
            'record intraop vitals', 'manage anaesthesia complications', 'record post anaesthesia reviews',

            // ── Nursing ──
            'manage nurse allocations', 'manage duty rosters', 'manage shift handovers',
            'manage nursing procedures', 'manage nurse departments',

            // ── Prescriptions & Medicines ──
            'create prescriptions', 'edit prescriptions', 'view prescriptions',
            'dispense medicines', 'verify dispensations', 'manage controlled drugs',
            'process drug returns', 'manage medicine categories', 'manage medicine brands',
            'manage medicine inventory', 'manage goods received notes',
            'generate expiry alerts', 'generate stock alerts',

            // ── Lab & Radiology ──
            'add test requests', 'view test results', 'print lab reports', 'download lab reports',
            'manage lab specimens', 'manage lab worklists', 'verify lab results',
            'manage test categories', 'manage lab management', 'manage lab technicians',
            'manage lab equipment', 'manage lab integration',
            'manage radiology schedules', 'manage radiology worklist', 'approve radiology reports',
            'manage radiology tests', 'manage radiology categories', 'record contrast administration',

            // ── Blood Bank ──
            'manage blood bank', 'manage blood donations', 'manage blood units',
            'perform crossmatch', 'manage blood requests', 'manage blood inventory',
            'report transfusion reactions',

            // ── Billing & Finance ──
            'create invoices', 'edit invoices', 'view invoices', 'add payments',
            'add refunds', 'view billing', 'view payments', 'manage payment methods',
            'manage advance payments', 'defer emergency billing',
            'manage expense categories', 'manage expenses', 'manage income',
            'view payment reports', 'view financial reports', 'generate financial reports',
            'generate billing reports', 'export journals', 'export ledgers', 'export reports',
            'manage bank accounts', 'manage journals', 'manage budgets',

            // ── SHA / Insurance ──
            'verify insurance', 'manage preauthorizations', 'manage insurance claims',
            'manage claim batches', 'manage claim rejections', 'manage claim remittances',
            'manage tariffs', 'manage benefit packages', 'manage insurance providers',
            'manage insurance API', 'manage insurance integration', 'manage insurance management',

            // ── Finance / Accounting ──
            'manage account heads', 'manage ledgers', 'manage trial balance',
            'manage chart of accounts', 'manage cash flow',

            // ── IPD/OPD ──
            'admit patients', 'manage patient vitals', 'manage patient notes',
            'assign nurses', 'manage discharge plans',

            // ── Maternity ──
            'manage anc registrations', 'manage pregnancies', 'manage labour records',
            'record deliveries', 'manage postnatal visits', 'manage family planning visits',
            'manage fp visits',

            // ── Neonatal ──
            'manage newborns', 'manage nicu admissions', 'manage incubator assignments',
            'manage neonatal feeds', 'record neonatal assessments', 'manage phototherapy',

            // ── Paediatrics ──
            'manage growth measurements', 'manage developmental assessments',
            'manage immunization schedules', 'manage child protection cases',

            // ── HIV ──
            'manage hts encounters', 'manage hiv care enrollments', 'manage art regimens',
            'manage viral load results', 'manage pep prep records', 'manage hei records',
            'manage partner notifications',

            // ── TB ──
            'manage tb screenings', 'manage tb cases', 'manage tb treatments',
            'manage tb adherence logs', 'manage tb contacts',

            // ── Oncology ──
            'manage cancer registrations', 'manage oncology treatment plans',
            'manage chemo protocols', 'manage chemo cycles', 'manage chemo infusions',
            'manage adverse events',

            // ── Public Health ──
            'manage surveillance cases', 'manage notifiable disease reports',
            'manage outbreak events', 'manage outbreak investigations',

            // ── Allied (Dental, Ophthalmology, ENT, Rehab, Nutrition) ──
            'manage dental records', 'manage eye examinations', 'manage ent records',
            'manage rehab sessions', 'manage nutrition records', 'manage meal orders',
            'manage diet orders',

            // ── Mental Health ──
            'manage mh assessments', 'manage mh treatment plans', 'manage counselling sessions',

            // ── Social Work ──
            'manage social assessments', 'manage welfare waivers', 'manage discharge plans',
            'manage family support',

            // ── Mortuary ──
            'manage mortuary records', 'manage mortuary slots', 'manage body identifications',
            'manage postmortems', 'issue death certificates', 'manage death records',

            // ── Ambulance ──
            'manage ambulances', 'manage ambulance calls', 'manage ambulance trips',
            'manage ambulance fuel', 'manage ambulance maintenance',

            // ── CSSD ──
            'manage cssd instruments', 'manage cssd cycles', 'manage sterilizer runs',
            'record sterility indicators', 'manage cssd issues', 'manage cssd returns',
            'manage cssd batches',

            // ── IPC ──
            'manage hai surveillance', 'manage isolation orders',
            'record hand hygiene observations', 'manage ipc audits',
            'record antibiotic usage',

            // ── Maintenance ──
            'manage maintenance requests', 'manage work orders', 'manage calibrations',
            'manage assets', 'transfer assets', 'dispose assets', 'manage medical equipment',

            // ── Security ──
            'manage security incidents', 'manage lost found items', 'manage access events',
            'manage visitor passes', 'manage visitor logs', 'manage vehicle access',
            'record emergency drills', 'manage risk assessments', 'manage safety incidents',
            'record fire inspections',

            // ── Medical Records ──
            'manage record requests', 'manage icd coding', 'upload scanned documents',
            'manage medical records', 'manage document templates', 'manage document types',
            'manage documents', 'manage mrd',

            // ── Doctors & Staff ──
            'manage staff profiles', 'view staff profiles', 'manage doctors',
            'manage nurses', 'manage receptionists', 'manage pharmacists',
            'manage lab technicians', 'manage accountants',
            'manage practitioner qualifications', 'manage practitioner licences',
            'manage privileges', 'manage oncall schedules', 'manage cme records',
            'manage staff documents',

            // ── Credentialing ──
            'manage credentials', 'manage licence renewals',

            // ── HR ──
            'manage employees', 'manage employee contracts', 'manage disciplinary records',
            'manage leave requests', 'manage leave types', 'manage payrolls',
            'manage payroll exports', 'view attendance', 'view payrolls',
            'manage recruitment', 'manage job postings', 'manage job applications',
            'manage training programs', 'manage appraisals',
            'manage shift types', 'manage roster builder',
            'manage hr reports', 'manage staff documents',

            // ── Referrals ──
            'manage referrals', 'manage referral documents',

            // ── Telemedicine ──
            'use telemedicine', 'manage tele participants',
            'manage telemedicine sessions', 'manage telemedicine integration',
            'manage telemedicine management',

            // ── Patient Portal ──
            'manage patient portal accounts', 'view patient portal',

            // ── Communication ──
            'send mass mails', 'send mass sms', 'manage notification templates',
            'manage notice board', 'manage contact messages', 'manage inquiries',
            'manage complaints', 'manage incidents',

            // ── Research ──
            'manage research protocols', 'manage research data',

            // ── Quality ──
            'manage quality indicators', 'manage indicator values',
            'manage corrective actions', 'manage compliance checklists',
            'manage quality reports', 'manage kpi snapshots', 'manage data quality issues',
            'manage mortality reviews',

            // ── Audit / Compliance ──
            'view audit logs', 'manage break glass events', 'view system logs',

            // ── ICT ──
            'manage it assets', 'manage it tickets', 'raise it tickets',
            'manage backups', 'manage software licences',
            'manage api tokens', 'manage API integration', 'manage API management',

            // ── Dashboard ──
            'view dashboard', 'view dashboard analytics', 'view analytics',
            'view branch analytics', 'manage dashboard definitions',

            // ── Config ──
            'manage hospital info', 'manage hospital branches',
            'manage system settings', 'manage user accounts', 'manage roles',
            'manage permissions', 'manage modules', 'manage feature flags',
            'manage integrations', 'manage settings',

            // ── Reports ──
            'view reports', 'generate patient reports', 'generate birth reports',
            'generate death reports', 'generate pathology reports',
            'manage report schedules', 'manage saved reports',
            'manage custom reports', 'manage khis reports',

            // ── CMS ──
            'manage homepage', 'manage services', 'manage doctors listing',
            'manage blog posts', 'manage blog categories', 'manage gallery items',
            'manage gallery categories', 'manage testimonials', 'manage careers',
            'manage inquiries', 'manage seo', 'manage header footer',
            'manage features', 'manage about', 'manage contact',

            // ── Multi-Hospital / Tenancy ──
            'manage branches', 'assign staff per branch',

            // ── AI & Advanced ──
            'use ai assistant', 'manage ai suggestions', 'manage AI features',
            'manage ai features', 'view ai insights', 'manage predictive analytics',

            // ── Marketing Suite ──
            'manage marketing', 'create marketing posts', 'edit marketing posts',
            'delete marketing posts', 'approve marketing posts', 'manage campaigns',
            'manage social accounts', 'schedule posts', 'manage comment replies',
            'manage graphic assets', 'access marketing analytics', 'manage seo',

            // ── Restored original permissions (sidebar/routes/tests depend on these) ──
            'approve test results', 'assign departments', 'assign doctors',
            'enter test results', 'issue blood units', 'manage ambulance crews',
            'manage appointment reminders', 'manage cashier sessions', 'manage charges',
            'manage compliance responses', 'manage data subject requests', 'manage discounts',
            'manage ethics approvals', 'manage fire equipment', 'manage fiscal periods',
            'manage journal entries', 'manage kitchen inventory', 'manage kpi definitions',
            'manage laundry batches', 'manage linen records', 'manage message campaigns',
            'manage message opt outs', 'manage number sequences', 'manage packages',
            'manage partograph entries', 'manage phototherapy sessions', 'manage portal dependants',
            'manage price lists', 'manage publications', 'manage referral feedback',
            'manage referring facilities', 'manage research projects', 'manage rfid tags',
            'manage salaries', 'manage schedule slots', 'manage services listing',
            'manage staff licences', 'manage tax rates', 'manage trainee assessments',
            'manage trainees', 'manage transfusions', 'manage waivers',
            'monitor iot sensors', 'post journal entries', 'reconcile bank accounts',
            'record kpi snapshots', 'reverse journal entries', 'send outbound messages',
            'send portal messages', 'view doctors', 'view portal access logs',
            'manage queue',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function roleList(): array
    {
        return [
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
    }

    private function assignPermissionsToRoles(): void
    {
        $allPermissions = Permission::pluck('id', 'name');
        $p = $allPermissions->keys()->toArray();
        $has = fn (array $subset) => array_values(array_intersect($p, $subset));

        $pivotRows = [];

        $add = function (string $roleName, array $permissionNames) use ($allPermissions, &$pivotRows) {
            $roleId = Role::where('name', $roleName)->value('id');
            if (!$roleId) {
                return;
            }
            foreach ($permissionNames as $permName) {
                $permId = $allPermissions[$permName] ?? null;
                if ($permId) {
                    $pivotRows[] = [
                        'permission_id' => $permId,
                        'role_id' => $roleId,
                    ];
                }
            }
        };

        // Super Admin — everything
        $superAdminId = Role::where('name', 'Super Admin')->value('id');
        foreach ($allPermissions as $permId) {
            $pivotRows[] = [
                'permission_id' => $permId,
                'role_id' => $superAdminId,
            ];
        }

        $add('Hospital Admin', $has([
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
            'complete who checklists', 'manage theatre teams', 'manage theatre consumables', 'manage recovery records',
            'manage anaesthesia assessments', 'manage anaesthesia records',
            'manage nurse allocations', 'manage duty rosters', 'manage shift handovers',
            'create prescriptions', 'edit prescriptions', 'view prescriptions', 'dispense medicines',
            'print lab reports', 'download lab reports', 'view test results',
            'manage lab specimens', 'manage lab worklists', 'verify lab results',
            'manage radiology schedules', 'manage radiology worklist', 'approve radiology reports',
            'manage blood bank', 'manage blood donations', 'manage blood units', 'perform crossmatch',
            'create invoices', 'edit invoices', 'add payments', 'view payment reports', 'manage insurance claims',
            'manage claim batches', 'manage claim rejections', 'manage claim remittances', 'manage tariffs',
            'manage anc registrations', 'manage pregnancies', 'manage labour records', 'record deliveries',
            'manage newborns', 'manage nicu admissions',
            'manage growth measurements', 'manage developmental assessments', 'manage immunization schedules',
            'manage hts encounters', 'manage hiv care enrollments', 'manage art regimens',
            'manage tb cases', 'manage tb treatments',
            'manage cancer registrations', 'manage oncology treatment plans',
            'manage social assessments', 'manage welfare waivers',
            'manage mortuary records', 'manage postmortems', 'issue death certificates',
            'manage cssd instruments', 'manage cssd cycles',
            'manage hai surveillance', 'manage isolation orders', 'manage ipc audits',
            'manage maintenance requests', 'manage work orders', 'manage calibrations', 'manage assets',
            'manage security incidents', 'manage visitor passes',
            'use telemedicine', 'manage complaints', 'manage incidents', 'manage mortality reviews',
            'manage compliance checklists',
            'manage it tickets',
            'manage hospital info', 'manage notification templates',
            'manage user accounts',
            'view analytics',
            'view dashboard analytics', 'view reports', 'generate patient reports',
            'view doctors', 'manage queue', 'manage packages', 'enter test results',
            'approve test results',
            'manage charges', 'manage discounts', 'manage schedule slots',
            'manage cashier sessions', 'manage number sequences',
            'manage ambulance crews', 'manage appointment reminders',
            'manage message campaigns', 'send outbound messages',
            'manage portal dependants', 'send portal messages', 'view portal access logs',
            'manage waivers', 'manage phototherapy sessions', 'manage partograph entries',
            'issue blood units', 'manage transfusions',
            'manage staff profiles', 'view staff profiles',
        ]));

        $add('Doctor', $has([
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
            'manage anaesthesia drugs', 'record intraop vitals',
            'manage anaesthesia complications', 'record post anaesthesia reviews',
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

        $add('Nurse', $has([
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
            'manage hai surveillance', 'manage isolation orders', 'record hand hygiene observations',
            'manage linen records',
            'view bed status', 'manage bed assignments',
            'view staff profiles', 'view attendance',
            'generate patient reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        $add('Receptionist', $has([
            'view patients', 'add patients', 'edit patients', 'upload documents',
            'emergency register patients',
            'create appointments', 'manage appointments', 'reschedule appointments', 'cancel appointments',
            'view doctor schedules', 'send appointment reminders',
            'manage triage queue', 'manage queue',
            // Spec: no unrestricted prescriptions for receptionist
            'view test results', 'print lab reports', 'download lab reports',
            'view doctors',
            'create invoices', 'edit invoices', 'add payments', 'add refunds',
            'verify insurance',
            'view billing', 'view payments',
            'generate patient reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        $add('Pharmacist', $has([
            'view patients',
            'view prescriptions', 'dispense medicines', 'verify dispensations',
            'manage controlled drugs', 'process drug returns',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'manage goods received notes', 'generate expiry alerts', 'generate stock alerts',
            'manage packages',
            'view test results',
            'generate billing reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        $add('Lab Technician', $has([
            'view patients',
            'view prescriptions',
            'add test requests', 'view test results', 'print lab reports', 'download lab reports',
            'manage lab specimens', 'manage lab worklists', 'verify lab results',
            'enter test results', 'approve test results',
            'manage test categories',
            'generate pathology reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        $add('Radiologist', $has([
            'view patients',
            'view test results', 'print lab reports', 'download lab reports',
            'manage radiology schedules', 'manage radiology worklist', 'approve radiology reports',
            'manage radiology tests', 'manage radiology categories', 'record contrast administration',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        $add('Accountant', $has([
            'view patients',
            'create invoices', 'edit invoices', 'view invoices', 'add payments', 'add refunds',
            'view billing', 'view payments', 'manage payment methods', 'manage advance payments',
            'manage expense categories', 'manage expenses', 'manage income',
            'view payment reports', 'view financial reports', 'generate financial reports',
            'generate billing reports', 'export journals', 'export ledgers',
            'manage insurance claims', 'manage claim batches', 'manage claim remittances',
            'view dashboard analytics', 'view analytics',
            'raise it tickets',
        ]));

        $add('Case Handler', $has([
            'view patients', 'add patients', 'edit patients', 'upload documents',
            'manage patient cases', 'manage case handlers', 'manage referral documents',
            'manage discharge plans', 'manage social assessments',
            'view appointments',
            'generate patient reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        $add('Ambulance Operator', $has([
            'view patients',
            'manage ambulances', 'manage ambulance calls', 'manage ambulance trips',
            'manage ambulance fuel', 'manage ambulance maintenance',
            'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        $add('HR Officer', $has([
            'view patients',
            'manage employees', 'manage employee contracts', 'manage disciplinary records',
            'manage leave requests', 'manage leave types', 'manage payrolls', 'manage payroll exports',
            'view attendance', 'view payrolls',
            'manage recruitment', 'manage job postings', 'manage job applications',
            'manage training programs', 'manage appraisals',
            'manage shift types', 'manage roster builder',
            'manage staff profiles', 'view staff profiles', 'manage staff documents',
            'view dashboard analytics', 'view analytics',
            'raise it tickets',
        ]));

        $add('Patient', $has([
            // Staff HMS routes are NOT for Patient users. Portal uses patient-portal/* session auth.
            // Minimal non-clinical permissions only — no staff AI/Elliana.
            'raise it tickets',
        ]));

        $add('System Auditor', $has([
            'view audit logs', 'view system logs',
            'view dashboard analytics', 'view analytics',
            'raise it tickets',
        ]));

        $add('Support Staff', $has([
            'view patients', 'view appointments', 'view dashboard analytics',
            'raise it tickets',
        ]));

        $add('Telemedicine Doctor', $has([
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

        $add('Inventory Manager', $has([
            'view patients',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'manage controlled drugs', 'process drug returns', 'manage goods received notes',
            'generate expiry alerts', 'generate stock alerts', 'manage packages',
            'manage cssd instruments', 'manage cssd cycles', 'manage sterilizer runs',
            'generate billing reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        $add('Procurement Officer', $has([
            'view patients',
            'manage medicine categories', 'manage medicine brands', 'manage medicine inventory',
            'manage goods received notes',
            'manage expenses',
            'manage staff profiles',
            'view financial reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
            'raise it tickets',
        ]));

        $add('IT Support', $has([
            'view patients',
            'manage it assets', 'manage it tickets', 'raise it tickets',
            'manage backups', 'manage software licences',
            'manage api tokens', 'manage API integration', 'manage API management',
            'manage integrations', 'manage rfid tags', 'monitor iot sensors',
            'view system logs', 'view dashboard analytics',
            'use ai assistant', 'view analytics',
        ]));

        $add('Marketing Manager', $has([
            'manage marketing', 'create marketing posts', 'edit marketing posts', 'delete marketing posts',
            'approve marketing posts', 'manage campaigns', 'manage social accounts', 'schedule posts',
            'manage comment replies', 'manage graphic assets', 'access marketing analytics', 'manage seo',
            'manage homepage', 'manage services', 'manage doctors listing',
            'manage blog posts', 'manage gallery items', 'manage testimonials',
            'view dashboard analytics', 'view analytics',
            'raise it tickets',
        ]));

        $add('System AI Bot', $has([
            // Machine account: read/suggest only. No audit, no staff profile, no clinical write.
            'use ai assistant',
            'manage ai suggestions',
            'view analytics',
            'view appointments',
            'view prescriptions',
            'view test results',
        ]));

        $add('Maternity Nurse', array_merge($has([
            'view patients', 'edit patients', 'upload documents',
            'view appointments',
            'manage triage records', 'view triage queue',
            'manage ward rounds', 'manage nursing notes', 'manage fluid balance',
            'manage medication administration', 'manage diet orders',
            'manage nurse allocations', 'manage duty rosters', 'manage shift handovers', 'manage nursing procedures',
            'view prescriptions', 'manage lab specimens',
            'admit patients', 'manage patient vitals', 'manage patient notes', 'update bed status',
            'view bed status', 'manage bed assignments',
            'view staff profiles', 'view attendance',
            'generate patient reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics', 'raise it tickets',
        ]), $has([
            'manage anc registrations', 'manage pregnancies', 'manage labour records', 'record deliveries',
            'manage postnatal visits', 'manage family planning visits',
            'manage newborns', 'manage nicu admissions',
        ])));

        $add('ICU Nurse', array_merge($has([
            'view patients', 'edit patients', 'upload documents',
            'view appointments',
            'manage triage records', 'view triage queue',
            'manage ward rounds', 'manage nursing notes', 'manage fluid balance',
            'manage medication administration', 'manage diet orders',
            'manage nurse allocations', 'manage duty rosters', 'manage shift handovers', 'manage nursing procedures',
            'view prescriptions', 'manage lab specimens',
            'admit patients', 'manage patient vitals', 'manage patient notes', 'update bed status',
            'view bed status', 'manage bed assignments',
            'view staff profiles', 'view attendance',
            'generate patient reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics', 'raise it tickets',
        ]), $has([
            'manage icu admissions', 'manage critical care charts', 'manage ventilators',
            'manage sedation scores', 'manage infusions', 'record abg results',
        ])));

        $add('Theatre Nurse', array_merge($has([
            'view patients', 'edit patients', 'upload documents',
            'view appointments',
            'manage triage records', 'view triage queue',
            'manage ward rounds', 'manage nursing notes', 'manage fluid balance',
            'manage medication administration', 'manage diet orders',
            'manage nurse allocations', 'manage duty rosters', 'manage shift handovers', 'manage nursing procedures',
            'view prescriptions', 'manage lab specimens',
            'admit patients', 'manage patient vitals', 'manage patient notes', 'update bed status',
            'view bed status', 'manage bed assignments',
            'view staff profiles', 'view attendance',
            'generate patient reports', 'view dashboard analytics',
            'use ai assistant', 'view analytics', 'raise it tickets',
        ]), $has([
            'manage theatre bookings', 'manage theatre teams', 'manage theatre consumables',
            'record preop assessments', 'complete who checklists', 'manage recovery records',
            'manage anaesthesia assessments', 'manage anaesthesia records',
            'manage anaesthesia drugs', 'record intraop vitals',
            'manage anaesthesia complications', 'record post anaesthesia reviews',
        ])));

        $add('CSSD Technician', $has([
            'view patients',
            'manage cssd instruments', 'manage cssd cycles', 'manage sterilizer runs',
            'record sterility indicators', 'manage cssd issues', 'manage cssd returns',
            'manage cssd batches',
            'view dashboard analytics', 'raise it tickets',
        ]));

        $add('Mortuary Attendant', $has([
            'view patients',
            'manage mortuary records', 'manage mortuary slots', 'manage body identifications',
            'manage postmortems', 'issue death certificates', 'manage death records',
            'view dashboard analytics', 'raise it tickets',
        ]));

        $add('Security Officer', $has([
            'view patients',
            'manage security incidents', 'manage lost found items', 'manage access events',
            'manage visitor passes', 'manage visitor logs', 'manage vehicle access',
            'manage safety incidents', 'record emergency drills',
            'view dashboard analytics', 'raise it tickets',
        ]));

        $add('Quality Officer', $has([
            'view patients', 'view appointments',
            'manage complaints', 'manage incidents', 'manage mortality reviews',
            'manage quality indicators', 'manage indicator values', 'manage corrective actions',
            'manage record requests', 'manage icd coding', 'manage khis reports', 'manage data quality issues',
            'generate patient reports', 'generate billing reports',
            'view dashboard analytics', 'view audit logs',
            'raise it tickets',
        ]));

        $add('Biomedical Engineer', $has([
            'view patients',
            'manage maintenance requests', 'manage work orders', 'manage calibrations',
            'manage assets', 'transfer assets', 'dispose assets',
            'manage medical equipment',
            'manage it assets',
            'view dashboard analytics',
            'raise it tickets',
        ]));

        $add('Dietitian', $has([
            'view patients',
            'manage nutrition records', 'manage diet orders', 'manage meal orders',
            'generate patient reports', 'view dashboard analytics',
            'raise it tickets',
        ]));

        $add('Social Worker', $has([
            'view patients',
            'manage social assessments', 'manage welfare waivers', 'manage discharge plans',
            'manage mh assessments', 'manage counselling sessions',
            'generate patient reports', 'view dashboard analytics',
            'raise it tickets',
        ]));

        $add('Mental Health Professional', $has([
            'view patients', 'edit patients',
            'create consultations', 'manage consultations', 'diagnose patients',
            'manage clinical notes',
            'manage mh assessments', 'manage mh treatment plans', 'manage counselling sessions',
            'create prescriptions', 'view prescriptions',
            'generate patient reports', 'view dashboard analytics',
            'raise it tickets',
        ]));

        // Replace (sync) role permissions to intended sets — remove over-permissions.
        $intendedByRole = [];
        foreach ($pivotRows as $row) {
            $intendedByRole[$row['role_id']][] = $row['permission_id'];
        }
        $allRoleIds = Role::pluck('id');
        foreach ($allRoleIds as $roleId) {
            $intended = array_values(array_unique($intendedByRole[$roleId] ?? []));
            DB::table('role_has_permissions')->where('role_id', $roleId)->delete();
            foreach (array_chunk($intended, 500) as $chunk) {
                DB::table('role_has_permissions')->insert(array_map(fn ($pid) => [
                    'permission_id' => $pid,
                    'role_id' => $roleId,
                ], $chunk));
            }
        }
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
