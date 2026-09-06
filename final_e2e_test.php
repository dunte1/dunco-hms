<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$baseUrl = 'https://hmse.duncowebsolutions.co.ke';

$testRoutes = [
    // Core modules
    'hms' => '/hms',
    'patients' => '/hms/patients',
    'doctors' => '/hms/doctors',
    'appointments' => '/hms/appointments',
    'opd' => '/hms/opd',
    'ipd' => '/hms/ipd',
    'beds' => '/hms/beds',
    'pharmacy' => '/hms/pharmacy',
    'laboratory' => '/hms/laboratory',
    'radiology' => '/hms/radiology',
    'billing' => '/hms/billing/invoices',
    'insurance' => '/hms/insurance',
    'bloodbank' => '/hms/bloodbank',
    'ambulance' => '/hms/ambulance',
    'hr' => '/hms/hr',
    'finance' => '/hms/finance',
    'inventory' => '/hms/inventory',
    'stores' => '/hms/stores',
    'queue' => '/hms/queue',
    'reports' => '/hms/reports',
    'settings' => '/hms/settings',
    'nurses' => '/hms/nurses',
    'ot' => '/hms/ot',
    'drug-interactions' => '/hms/drug-interactions',
    'consent' => '/hms/consent',
    'mrd' => '/hms/mrd',
    'vaccination' => '/hms/vaccination',
    'mortuary' => '/hms/mortuary',
    'equipment' => '/hms/equipment',
    'telemedicine' => '/hms/telemedicine',
    'visitors' => '/hms/visitors',
    'system-users' => '/hms/system/users',
    // New PDF routes
    'stock-take' => '/hms/inventory/stock-take',
    'birth-death' => '/hms/birth-reports',
    'discharge-summary' => '/hms/discharge-summary',
    'shift-roster' => '/hms/hr/shifts/roster-pdf',
    'training' => '/hms/hr/training-programs',
];

$roles = [
    'Super Admin' => ['admin@example.com', 'admin123'],
    'Hospital Admin' => ['hospital@duncohms.com', 'admin123'],
    'Doctor' => ['dr.mwangi@duncohms.com', 'doctor123'],
    'Nurse' => ['nurse.njeri@duncohms.com', 'nurse123'],
    'Receptionist' => ['reception.kimani@duncohms.com', 'password'],
    'Pharmacist' => ['pharmacist.otieno@duncohms.com', 'password'],
    'Lab Tech' => ['lab.wekesa@duncohms.com', 'password'],
    'Accountant' => ['accountant.ogutu@duncohms.com', 'password'],
    'HR Officer' => ['hr@duncohms.com', 'password'],
    'Inventory Mgr' => ['inventory@duncohms.com', 'password'],
    'Patient' => ['patient.kamau@duncohms.com', 'password'],
];

echo "=== COMPREHENSIVE E2E TEST ===\n";
echo "Date: " . now()->format('Y-m-d H:i:s') . "\n";
echo "Server: {$baseUrl}\n\n";

// Test each role
foreach ($roles as $role => [$email, $password]) {
    // Login
    $loginResp = \Illuminate\Support\Facades\Http::retry(2, 2000)->post($baseUrl . '/api/login', [
        'email' => $email, 'password' => $password
    ]);

    if ($loginResp->status() !== 200) {
        echo "SKIP: {$role} - login failed (HTTP {$loginResp->status()})\n";
        continue;
    }

    $token = $loginResp->json('token');
    $accessible = 0;
    $denied = 0;
    $failed = [];

    foreach ($testRoutes as $name => $url) {
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->withHeaders(['Accept' => 'text/html'])
            ->get($baseUrl . $url);
        $status = $response->status();
        if ($status === 200 || $status === 302) {
            $accessible++;
        } else {
            $denied++;
            $failed[] = "{$name}({$status})";
        }
    }

    $total = count($testRoutes);
    $pct = round(($accessible / $total) * 100);
    echo "{$role}: {$accessible}/{$total} ({$pct}%)";
    if (!empty($failed)) {
        echo " | Issues: " . implode(', ', array_slice($failed, 0, 3));
    }
    echo "\n";
}

echo "\n=== TEST COMPLETE ===\n";
