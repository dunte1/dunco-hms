<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Routes to test
$adminRoutes = [
    '/dashboard', '/hms/patients', '/hms/appointments', '/hms/doctors',
    '/hms/billing/invoices', '/hms/pharmacy/medicines', '/hms/laboratory/tests',
    '/hms/laboratory/requests', '/hms/radiology/tests', '/hms/admissions',
    '/hms/beds', '/hms/blood-bank', '/hms/ambulance', '/hms/nurses',
    '/hms/employees', '/hms/payroll', '/hms/attendance', '/hms/leave-requests',
    '/hms/insurance/providers', '/hms/insurance/claims', '/hms/sha',
    '/hms/reports', '/hms/settings', '/admin/roles', '/admin/notices',
    '/hms/finance/accounts', '/hms/finance/expenses', '/hms/finance/income',
    '/hms/daily-summary', '/hms/telemedicine', '/hms/rfid', '/hms/iot',
    '/hms/visitors', '/hms/eprescription', '/hms/case-handlers',
    '/hms/birth-death-reports', '/hms/operation-reports',
    '/hms/diagnosis', '/hms/discharge-summary', '/hms/icd10',
    '/hms/reports/patient', '/hms/reports/revenue',
    '/marketing', '/hms/settings/audit-logs',
];

$doctorRoutes = ['/dashboard', '/hms/patients', '/hms/appointments', '/hms/doctors'];

$nurseRoutes = ['/dashboard', '/hms/patients', '/hms/appointments'];

$receptionRoutes = ['/dashboard', '/hms/patients', '/hms/appointments'];

$pharmacistRoutes = ['/dashboard', '/hms/pharmacy/medicines'];

$labRoutes = ['/dashboard', '/hms/laboratory/tests', '/hms/laboratory/requests'];

$accountantRoutes = ['/dashboard', '/hms/billing/invoices', '/hms/finance/accounts'];

$hrRoutes = ['/dashboard', '/hms/employees', '/hms/payroll', '/hms/attendance'];

$patientRoutes = ['/dashboard'];

$users = [
    ['email' => 'admin@duncohms.com', 'password' => 'admin123', 'role' => 'Super Admin', 'routes' => $adminRoutes],
    ['email' => 'dr.mwangi@duncohms.com', 'password' => 'doctor123', 'role' => 'Doctor', 'routes' => $doctorRoutes],
    ['email' => 'nurse.njeri@duncohms.com', 'password' => 'nurse123', 'role' => 'Nurse', 'routes' => $nurseRoutes],
    ['email' => 'reception.kimani@duncohms.com', 'password' => 'reception123', 'role' => 'Receptionist', 'routes' => $receptionRoutes],
    ['email' => 'pharmacist.otieno@duncohms.com', 'password' => 'pharmacist123', 'role' => 'Pharmacist', 'routes' => $pharmacistRoutes],
    ['email' => 'lab.wekesa@duncohms.com', 'password' => 'labtech123', 'role' => 'Lab Tech', 'routes' => $labRoutes],
    ['email' => 'accountant.ogutu@duncohms.com', 'password' => 'accountant123', 'role' => 'Accountant', 'routes' => $accountantRoutes],
    ['email' => 'hr@duncohms.com', 'password' => 'hr123', 'role' => 'HR Officer', 'routes' => $hrRoutes],
    ['email' => 'patient.kamau@duncohms.com', 'password' => 'patient123', 'role' => 'Patient', 'routes' => $patientRoutes],
];

echo "========================================\n";
echo "  DUNCO HMS - ROUTE TESTING\n";
echo "========================================\n\n";

$totalTests = 0;
$passed = 0;
$failed = 0;
$errors = [];

foreach ($users as $userData) {
    echo "----------------------------------------\n";
    echo "Testing: {$userData['role']} ({$userData['email']})\n";
    echo "----------------------------------------\n";
    
    // Use artisan tinker approach: simulate request via route()
    $user = User::where('email', $userData['email'])->first();
    if (!$user) {
        echo "  USER NOT FOUND!\n";
        $failed++;
        $errors[] = "{$userData['role']}: User not found";
        continue;
    }
    
    Auth::login($user);
    echo "  Logged in as: {$user->name} ({$user->email})\n";
    
    foreach ($userData['routes'] as $route) {
        $totalTests++;
        try {
            $response = \Illuminate\Support\Facades\Route::dispatch(
                \Illuminate\Http\Request::create($route, 'GET')
            );
            $status = $response->getStatusCode();
            
            if ($status == 200) {
                echo "  $route: 200 OK\n";
                $passed++;
            } elseif ($status == 302) {
                $loc = $response->getTargetUrl() ?? '';
                echo "  $route: 302 -> $loc\n";
                $passed++;
            } elseif ($status == 403) {
                echo "  $route: 403 FORBIDDEN\n";
                $passed++;
            } elseif ($status == 404) {
                echo "  $route: 404 NOT FOUND\n";
                $failed++;
                $errors[] = "{$userData['role']}: $route returned 404";
            } elseif ($status == 500) {
                echo "  $route: 500 SERVER ERROR\n";
                $failed++;
                $errors[] = "{$userData['role']}: $route returned 500";
            } else {
                echo "  $route: $status\n";
                if ($status >= 400) {
                    $failed++;
                    $errors[] = "{$userData['role']}: $route returned $status";
                } else {
                    $passed++;
                }
            }
        } catch (\Throwable $e) {
            echo "  $route: EXCEPTION - " . $e->getMessage() . "\n";
            $failed++;
            $errors[] = "{$userData['role']}: $route exception: " . $e->getMessage();
        }
    }
    
    Auth::logout();
    echo "\n";
}

echo "========================================\n";
echo "  RESULTS\n";
echo "========================================\n";
echo "Total tests: $totalTests\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n\n";

if (!empty($errors)) {
    echo "ERRORS TO FIX:\n";
    foreach ($errors as $err) {
        echo "  - $err\n";
    }
} else {
    echo "ALL TESTS PASSED!\n";
}
