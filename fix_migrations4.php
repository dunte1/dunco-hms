<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::table('migrations')->insert([
    'migration' => '2026_09_04_000001_add_status_and_profile_fields_to_users_table',
    'batch' => 12,
]);
echo "Marked second status migration as done\n";
