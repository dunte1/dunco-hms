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

// Test RFID create
try {
    $r = \Illuminate\Support\Facades\Route::dispatch(\Illuminate\Http\Request::create('/hms/rfid/create', 'GET'));
    echo "RFID: " . $r->getStatusCode() . "\n";
} catch (\Throwable $e) {
    echo "RFID Exception: " . $e->getMessage() . "\n";
}

// Test IoT create
try {
    $r = \Illuminate\Support\Facades\Route::dispatch(\Illuminate\Http\Request::create('/hms/iot/sensor/create', 'GET'));
    echo "IoT: " . $r->getStatusCode() . "\n";
} catch (\Throwable $e) {
    echo "IoT Exception: " . $e->getMessage() . "\n";
}

// Check log
$log = file_get_contents(storage_path('logs/laravel.log'));
echo "\nLog (" . strlen($log) . " bytes):\n";
echo substr($log, -500) . "\n";

Auth::logout();
