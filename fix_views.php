<?php
$dir = __DIR__ . '/resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$fixed = 0;

$broken = 'SystemSetting::get("hospital_name", config("app.name", "DuncoHMS")) ]}}';
$correct = 'SystemSetting::get("hospital_name", config("app.name", "DuncoHMS")) ]};
        $themeSettings = [
            "primary_color" => \App\Models\SystemSetting::get("primary_color", "#10b981"),
            "hospital_logo" => \App\Models\SystemSetting::get("hospital_logo", ""),
            "hospital_name" => \App\Models\SystemSetting::get("hospital_name", config("app.name", "DuncoHMS")),
            "hospital_address" => \App\Models\SystemSetting::get("hospital_address", ""),
            "hospital_phone" => \App\Models\SystemSetting::get("hospital_phone", ""),
            "hospital_email" => \App\Models\SystemSetting::get("hospital_email", ""),
        ]';

foreach ($files as $file) {
    if ($file->getExtension() !== 'blade.php') continue;
    $content = file_get_contents($file->getPathname());
    
    if (strpos($content, $broken) !== false) {
        $newContent = str_replace($broken, $correct, $content);
        file_put_contents($file->getPathname(), $newContent);
        echo "Fixed: " . $file->getFilename() . "\n";
        $fixed++;
    }
}

echo "\nFixed {$fixed} files\n";
