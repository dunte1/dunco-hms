<?php
$files = glob(__DIR__ . '/database/migrations/2026_09_28_*.php');
$count = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    // Fix non-nullable timestamps that don't already have ->nullable()
    $content = preg_replace('/->timestamp\(\'([^\']+)\'\)(?!->nullable)/', "->timestamp('$1')->nullable()", $content);
    if ($content !== $original) {
        file_put_contents($file, $content);
        $count++;
        echo "Fixed: " . basename($file) . "\n";
    }
}
echo "Total fixed: $count files\n";
