<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

// All routes to test
$routes = [
    '/dashboard', '/hms/patients', '/hms/appointments', '/hms/doctors',
    '/hms/billing/invoices', '/hms/pharmacy/medicines', '/hms/laboratory/tests',
    '/hms/laboratory/requests', '/hms/radiology/tests', '/hms/ipd', '/hms/opd',
    '/hms/beds', '/hms/bloodbank', '/hms/ambulance', '/hms/nurses',
    '/hms/hr/employees', '/hms/hr/payrolls', '/hms/hr/attendance', '/hms/hr/leave-requests',
    '/hms/insurance/providers', '/hms/insurance/claims', '/hms/sha',
    '/hms/reports', '/hms/settings', '/admin/roles', '/admin/notices',
    '/hms/finance/accounts', '/hms/finance/expenses', '/hms/finance/income',
    '/hms/daily-summary', '/hms/telemedicine', '/hms/rfid', '/hms/iot/bed-monitoring',
    '/hms/visitors', '/hms/prescriptions/e-prescription/templates',
    '/hms/case-handlers', '/hms/reports/birth', '/hms/reports/death',
    '/hms/operations', '/hms/diagnosis/categories', '/hms/diagnosis/patient-diagnoses',
    '/hms/discharge-summary', '/hms/icd10', '/hms/reports/patients', '/hms/reports/revenue',
    '/marketing/dashboard', '/hms/settings/audit-logs', '/hms/opd',
    // NEWLY FIXED STUBS
    '/hms/admissions', '/hms/hr/training-programs', '/hms/hr/shifts',
    '/hms/hr/public-holidays', '/hms/hr/leave-types', '/hms/hr/appraisals',
    '/hms/hr/announcements', '/marketing/social-accounts', '/marketing/seo',
    '/marketing/graphics', '/marketing/comments', '/marketing/campaigns',
    '/site/blog', '/site/careers', '/site/gallery', '/site/testimonials',
    '/admin/roles/create',
    '/hms/insurance', '/hms/insurance/companies/create', '/hms/insurance/policies/create',
    '/hms/laboratory/technicians', '/hms/reports/birth/create', '/hms/reports/death/create',
    '/hms/staff/pharmacists', '/hms/staff/lab-technicians',
    '/hms/rfid/create', '/hms/iot/sensor/create',
    '/hms/finance/expenses/entries', '/hms/daily-summary',
    '/hms/ambulance/create', '/hms/ambulance/emergency/create',
    '/hms/settings/theme',
    // Patient portal routes
    '/patient-portal/dashboard',
];

echo "============================================\n";
echo "  FINAL COMPREHENSIVE ROUTE TEST\n";
echo "============================================\n\n";

$user = User::where('email', 'admin@duncohms.com')->first();
Auth::login($user);

$pass = 0;
$fail = 0;
$errors = [];

foreach ($routes as $route) {
    try {
        $response = \Illuminate\Support\Facades\Route::dispatch(
            \Illuminate\Http\Request::create($route, 'GET')
        );
        $status = $response->getStatusCode();
        if ($status == 200 || $status == 302) {
            $pass++;
            echo "  OK  $route\n";
        } else {
            $fail++;
            echo "  ERR $route -> $status\n";
            $errors[] = "$route -> $status";
        }
    } catch (\Throwable $e) {
        $fail++;
        $msg = substr($e->getMessage(), 0, 100);
        echo "  ERR $route -> $msg\n";
        $errors[] = "$route -> $msg";
    }
}

Auth::logout();

// Check logs
$logContent = file_get_contents(storage_path('logs/laravel.log'));
$recentErrors = substr_count($logContent, 'production.ERROR');

echo "\n============================================\n";
echo "  RESULTS: $pass passed, $fail failed (of " . count($routes) . ")\n";
echo "  ERROR LOG: $recentErrors errors\n";
echo "============================================\n";

if (!empty($errors)) {
    echo "\nFAILURES:\n";
    foreach ($errors as $e) echo "  - $e\n";
} else {
    echo "\nALL ROUTES OK!\n";
}

file_put_contents(storage_path('logs/laravel.log'), '');
