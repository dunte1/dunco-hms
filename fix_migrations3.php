<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Remove the fake migration records and actually run the missing ones
$migrationsToReset = [
    '2025_11_01_181023_add_status_field_to_users_table',
    '2026_09_04_000001_add_status_and_profile_fields_to_users_table',
];

foreach ($migrationsToReset as $m) {
    DB::table('migrations')->where('migration', $m)->delete();
    echo "Removed: $m\n";
}

echo "\nNow run: php artisan migrate --force\n";
