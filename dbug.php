<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\User; use Illuminate\Support\Facades\Auth;
$user = User::where('email', 'admin@duncohms.com')->first();
Auth::login($user);
try {
    $r = \Illuminate\Support\Facades\Route::dispatch(\Illuminate\Http\Request::create('/hms/reports/bed-occupancy', 'GET'));
    echo "Status: " . $r->getStatusCode() . "\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
$log = file_get_contents(storage_path('logs/laravel.log'));
echo substr($log, -500) . "\n";
Auth::logout();
