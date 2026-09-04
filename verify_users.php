<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::table('users')->whereNull('email_verified_at')->update(['email_verified_at' => now()]);
echo "All users email verified\n";

$count = DB::table('users')->count();
$verified = DB::table('users')->whereNotNull('email_verified_at')->count();
echo "Total users: $count, Verified: $verified\n";
