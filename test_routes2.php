<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Test telemedicine as admin
$user = User::where('email', 'admin@duncohms.com')->first();
Auth::login($user);

$routes = [
    '/hms/telemedicine',
    '/hms/iot/bed-monitoring',
    '/marketing/dashboard',
    '/hms/reports/patients',
    '/hms/bloodbank',
    '/hms/ipd',
    '/hms/hr/employees',
    '/hms/hr/payrolls',
    '/hms/hr/attendance',
    '/hms/hr/leave-requests',
    '/hms/operations',
    '/hms/reports/birth',
    '/hms/reports/death',
    '/hms/diagnosis/categories',
    '/hms/diagnosis/patient-diagnoses',
    '/hms/prescriptions/e-prescription/templates',
    '/hms/birth-death-reports',
];

echo "Testing problematic routes:\n\n";

foreach ($routes as $route) {
    try {
        $response = \Illuminate\Support\Facades\Route::dispatch(
            \Illuminate\Http\Request::create($route, 'GET')
        );
        $status = $response->getStatusCode();
        if ($status == 200) {
            echo "  $route: 200 OK\n";
        } elseif ($status == 500) {
            echo "  $route: 500 ERROR\n";
            // Get error from log
            $log = tail(storage_path('logs/laravel.log'), 5);
            echo "    Latest log: " . substr($log, -200) . "\n";
        } else {
            echo "  $route: $status\n";
        }
    } catch (\Throwable $e) {
        echo "  $route: EXCEPTION - " . $e->getMessage() . "\n";
    }
}

function tail($file, $lines = 1) {
    $handle = fopen($file, "r");
    $lineCount = 0;
    $buffer = "";
    while (!feof($handle)) {
        $chunk = fread($handle, 4096);
        $buffer = $chunk . $buffer;
        $lineCount += substr_count($chunk, "\n");
        if ($lineCount >= $lines) break;
    }
    fclose($handle);
    $bufferLines = explode("\n", $buffer);
    return implode("\n", array_slice($bufferLines, -$lines));
}
