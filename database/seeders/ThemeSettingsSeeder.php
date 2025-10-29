<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class ThemeSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default theme settings if they don't exist
        $defaultSettings = [
            'primary_color' => ['#6366f1', 'Primary brand color'],
            'secondary_color' => ['#8b5cf6', 'Secondary brand color'],
            'hospital_logo' => ['', 'Hospital logo'],
            'favicon' => ['', 'Favicon'],
            'dark_mode' => [false, 'Dark mode enabled'],
        ];

        foreach ($defaultSettings as $key => $data) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data[0],
                    'type' => $key === 'dark_mode' ? 'boolean' : 'string',
                    'description' => $data[1],
                    'is_public' => false,
                ]
            );
        }
    }
}
