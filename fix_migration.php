<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::table('migrations')
    ->where('migration', '2025_10_20_022300_create_biometric_tables')
    ->update(['batch' => 1]);

echo "Biometric migration marked as done\n";
