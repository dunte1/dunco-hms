<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::where('email', 'admin@duncohms.com')->first();
Auth::login($user);

$problemRoutes = [
    '/hms/reports/birth/create',
    '/hms/reports/death/create',
    '/hms/rfid/create',
    '/hms/iot/sensor/create',
];

foreach ($problemRoutes as $route) {
    echo "Testing: $route\n";
    try {
        $response = \Illuminate\Support\Facades\Route::dispatch(
            \Illuminate\Http\Request::create($route, 'GET')
        );
        echo "  Status: " . $response->getStatusCode() . "\n";
    } catch (\Throwable $e) {
        echo "  Exception: " . $e->getMessage() . "\n";
    }
}

// Show last errors
$log = file_get_contents(storage_path('logs/laravel.log'));
$lines = explode("\n", $log);
$recentErrors = array_filter($lines, function($l) { return str_contains($l, 'production.ERROR'); });
echo "\nRecent errors:\n";
foreach (array_slice($recentErrors, -5) as $line) {
    echo "  " . substr($line, 0, 200) . "\n";
}

Auth::logout();
