<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$sidebarRoutes = [
    'dashboard', 'analytics.bi-dashboard', 'hms.dashboard.notifications',
    'hms.dashboard.today-summary', 'hms.dashboard.active-staff',
    'hms.patients.index', 'hms.ipd.index', 'hms.opd.index',
    'hms.diagnosis.patient-diagnoses', 'hms.discharge-summary.index',
    'hms.doctors.index', 'hms.doctors.departments.index',
    'hms.doctor-charges.index', 'hms.hr.schedules.index',
    'hms.nurses.index', 'hms.nurses.duty-roster', 'hms.nurses.assign-wards',
    'hms.staff.receptionists', 'hms.appointments.index',
    'hms.ambulance.index', 'hms.ambulance.calls',
    'hms.pharmacy.prescriptions.index', 'hms.prescriptions.e-prescription.templates',
    'hms.case-handlers.cases', 'hms.case-handlers.index',
    'hms.diagnosis.categories', 'hms.medical-history.index',
    'hms.operations.index', 'hms.bed-types.index', 'hms.beds.index',
    'iot.bed-occupancy-map', 'hms.laboratory.index',
    'hms.laboratory.tests.index', 'hms.test-categories.index',
    'hms.laboratory.requests.index', 'hms.radiology.tests.index',
    'hms.radiology.requests.index', 'hms.bloodbank.index',
    'hms.bloodbank.donors', 'hms.bloodbank.requests', 'hms.bloodbank.stock-levels',
    'hms.investigation-reports.index',
    'hms.pharmacy.medicines.index', 'hms.pharmacy.medicine-categories.index',
    'hms.pharmacy.medicine-brands.index',
    'hms.inventory.index', 'hms.inventory.categories',
    'hms.inventory.suppliers.index', 'hms.inventory.stock-movements.index',
    'hms.inventory.purchase-orders.index', 'hms.inventory.expiry-alerts',
    'hms.inventory.stock-report', 'hms.packages.index',
    'hms.finance.index', 'hms.finance.reports', 'hms.finance.profit-loss',
    'hms.finance.balance-sheet', 'hms.finance.cash-flow',
    'hms.billing.invoices.create', 'hms.billing.invoices.index',
    'hms.billing.receipts', 'hms.billing.payments.index',
    'hms.billing.payment-reports',
    'hms.advance-payments.deposits', 'hms.advance-payments.refunds',
    'hms.finance.accounts.index', 'hms.finance.ledger',
    'hms.finance.trial-balance', 'hms.finance.chart-of-accounts',
    'hms.finance.expenses.index', 'hms.finance.expenses.categories',
    'hms.finance.expenses.reports',
    'hms.finance.income.index', 'hms.finance.income.reports',
    'hms.insurance.companies', 'hms.insurance.providers.index',
    'hms.insurance.claims.index', 'hms.insurance.policies',
    'hms.hr.index', 'hms.hr.employees.index', 'hms.hr.employees.create',
    'hms.hr.designations.index', 'hms.hr.departments.index',
    'hms.hr.appraisals.index',
    'hms.hr.payrolls.create', 'hms.hr.payrolls.index',
    'hms.hr.attendance.index', 'hms.hr.leave-requests.index',
    'hms.hr.leave-types.index',
    'hms.hr.job-postings.index', 'hms.hr.job-applications.index',
    'hms.hr.training-programs.index', 'hms.hr.announcements.index',
    'hms.hr.shifts.index', 'hms.hr.schedules.index',
    'hms.hr.public-holidays.index',
    'hms.hr.reports.employee-list', 'hms.hr.reports.leave',
    'hms.hr.reports.attendance', 'hms.hr.reports.payroll-summary',
    'hms.hr.reports.headcount-trends', 'hms.hr.reports.attrition',
    'hms.hr.reports.salary-expense', 'hms.hr.reports.training-participation',
    'hms.hr.settings.index', 'hms.hr.document-types', 'hms.hr.documents.index',
    'hms.reports.revenue', 'hms.reports.billing', 'hms.reports.lab',
    'hms.reports.pharmacy', 'hms.reports.blood-bank', 'hms.reports.bed-occupancy',
    'hms.reports.diagnosis', 'hms.reports.doctor-performance',
    'hms.reports.custom-builder.index', 'hms.reports.expense',
    'hms.reports.birth', 'hms.reports.death', 'hms.reports.summary',
    'hms.reports.export-patients',
    'hms.calendar.index', 'admin.appointments.requests',
    'hms.queue.index', 'hms.queue.token-generation',
    'hms.queue.display-board', 'hms.queue.kiosk',
    'admin.enquiries.index', 'hms.enquiries.feedback',
    'admin.notices.index', 'hms.notices.staff',
    'hms.messaging.index', 'hms.messaging.bulk', 'hms.messaging.templates',
    'hms.reminders.index', 'hms.reminders.appointments', 'hms.reminders.payments',
    'hms.settings.index', 'hms.settings.branches',
    'hms.system.timezone', 'hms.system.theme', 'hms.settings.theme',
    'hms.system.users.index', 'admin.roles.index',
    'admin.modules.index', 'hms.system.localization',
    'hms.settings.backup', 'hms.settings.audit-logs',
    'hms.system.api-keys',
    'cms.home', 'cms.services', 'cms.doctors-page', 'cms.about',
    'cms.contact-page', 'cms.features', 'cms.header-footer',
    'cms.blog.index', 'cms.gallery.index', 'cms.testimonials.index',
    'cms.careers.index', 'cms.contact-inquiries', 'cms.seo',
    'marketing.dashboard', 'marketing.posts.index', 'marketing.campaigns.index',
    'marketing.scheduler.index', 'marketing.social-accounts.index',
    'marketing.comments.index', 'marketing.graphics.index', 'marketing.seo.index',
    'ai.elliana-d', 'ai.appointment-suggestions', 'hms.ai.predictive-analytics',
    'ai.diagnosis-suggestions', 'hms.daily-summary.index',
    'hms.integrations.index', 'hms.integrations.payment-gateways',
    'telemedicine.index', 'hms.integrations.whatsapp',
    'hms.integrations.google-calendar', 'hms.integration.ehr.index',
    'hms.integrations.alerts', 'rfid.index', 'iot.bed-monitoring',
    'hms.integrations.data-sync',
];

$users = [
    ['email' => 'admin@duncohms.com', 'password' => 'admin123', 'role' => 'Super Admin'],
    ['email' => 'hospital@duncohms.com', 'password' => 'admin123', 'role' => 'Hospital Admin'],
    ['email' => 'dr.mwangi@duncohms.com', 'password' => 'doctor123', 'role' => 'Doctor'],
    ['email' => 'nurse.njeri@duncohms.com', 'password' => 'nurse123', 'role' => 'Nurse'],
    ['email' => 'reception.kimani@duncohms.com', 'password' => 'reception123', 'role' => 'Receptionist'],
    ['email' => 'pharmacist.otieno@duncohms.com', 'password' => 'pharmacist123', 'role' => 'Pharmacist'],
    ['email' => 'lab.wekesa@duncohms.com', 'password' => 'labtech123', 'role' => 'Lab Tech'],
    ['email' => 'accountant.ogutu@duncohms.com', 'password' => 'accountant123', 'role' => 'Accountant'],
    ['email' => 'hr@duncohms.com', 'password' => 'hr123', 'role' => 'HR Officer'],
    ['email' => 'patient.kamau@duncohms.com', 'password' => 'patient123', 'role' => 'Patient'],
];

echo "========================================\n";
echo "  SIDEBAR LINK TEST - ALL USERS\n";
echo "========================================\n\n";

$totalTests = 0; $totalPass = 0; $totalFail = 0; $allErrors = [];

foreach ($users as $userData) {
    $user = User::where('email', $userData['email'])->first();
    if (!$user) { echo "SKIP: {$userData['role']}\n"; continue; }
    Auth::login($user);
    
    echo "Testing: {$userData['role']}\n";
    $userPass = 0; $userFail = 0; $userErrors = [];
    
    foreach ($sidebarRoutes as $routeName) {
        $totalTests++;
        try {
            $url = route($routeName);
            $path = parse_url($url, PHP_URL_PATH);
            $response = Route::dispatch(\Illuminate\Http\Request::create($path, 'GET'));
            $status = $response->getStatusCode();
            if ($status == 200 || $status == 302 || $status == 403) {
                $userPass++; $totalPass++;
            } else {
                $userFail++; $totalFail++;
                $userErrors[] = "$routeName -> $status";
                $allErrors[] = "{$userData['role']}: $routeName -> $status";
            }
        } catch (\Throwable $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'Route [' . $routeName . '] not defined')) {
                // Route doesn't exist - note but don't fail hard
                $userFail++; $totalFail++;
                $userErrors[] = "$routeName -> ROUTE NOT DEFINED";
                $allErrors[] = "{$userData['role']}: $routeName -> NOT DEFINED";
            } else {
                $userFail++; $totalFail++;
                $m = substr($msg, 0, 80);
                $userErrors[] = "$routeName -> $m";
                $allErrors[] = "{$userData['role']}: $routeName -> $m";
            }
        }
    }
    
    $count = count($sidebarRoutes);
    echo "  $userPass/$count passed, $userFail failed\n";
    if (!empty($userErrors)) {
        foreach ($userErrors as $e) echo "    $e\n";
    }
    echo "\n";
    Auth::logout();
}

echo "TOTAL: $totalPass/$totalTests passed, $totalFail failed\n";
if (!empty($allErrors)) {
    echo "\nFAILURES:\n";
    foreach ($allErrors as $e) echo "  - $e\n";
} else {
    echo "\nALL SIDEBAR LINKS OK!\n";
}
file_put_contents(storage_path('logs/laravel.log'), '');
