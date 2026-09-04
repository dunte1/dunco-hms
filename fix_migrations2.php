<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all pending migrations and mark them as ran
$pending = DB::select("SELECT migration FROM migrations WHERE migration NOT IN (SELECT migration FROM migrations WHERE batch > 0) ORDER BY migration");

// Actually, let's find all pending ones properly
$allMigrations = DB::table('migrations')->pluck('migration')->toArray();

$dir_path = $dir . '/database/migrations';
$files = scandir($dir_path);
$pendingMigrations = [];
foreach ($files as $file) {
    if (str_ends_with($file, '.php')) {
        $migrationName = str_replace('.php', '', $file);
        if (!in_array($migrationName, $allMigrations)) {
            $pendingMigrations[] = $migrationName;
        }
    }
}

echo "Found " . count($pendingMigrations) . " pending migrations\n";

foreach ($pendingMigrations as $migration) {
    DB::table('migrations')->insert([
        'migration' => $migration,
        'batch' => 11,
    ]);
    echo "Marked: $migration\n";
}

echo "\nDone! Run: php artisan migrate --force\n";
