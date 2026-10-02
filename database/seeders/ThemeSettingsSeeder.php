<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class ThemeSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Branding colors — DuncoHMS production/dev blue (matches app-layout defaults)
            'primary_color' => ['#000075', 'string', 'Primary brand color (sidebar header, buttons, accents)', true],
            'secondary_color' => ['#000054', 'string', 'Secondary brand color (gradient companion)', true],
            'accent_color' => ['#2563EB', 'string', 'Accent color (links, highlights)', true],
            'text_color' => ['#333333', 'string', 'Primary text color', true],

            // Sidebar Colors — deep navy (matches app default #00001A family)
            'sidebar_bg' => ['#00001A', 'string', 'Sidebar background color (deep navy)', true],
            'sidebar_text' => ['rgba(255,255,255,0.75)', 'string', 'Sidebar text color (soft white)', true],
            'sidebar_muted' => ['rgba(255,255,255,0.5)', 'string', 'Sidebar muted text color', true],
            'sidebar_hover' => ['rgba(255,255,255,0.09)', 'string', 'Sidebar hover background color', true],

            // UI Colors
            'navbar_border' => ['#E5ECEB', 'string', 'Top navbar border color', true],
            'main_bg' => ['#F7F9FA', 'string', 'Main content background color', true],

            // Hospital identity
            'hospital_name' => ['Dunco HMS', 'string', 'Hospital name displayed everywhere', true],
            'hospital_short_name' => ['DCH', 'string', 'Short name for badges/documents', true],
            'hospital_address' => ['Hospital Road, Nairobi, Kenya', 'string', 'Hospital physical address', true],
            'hospital_phone' => ['+254 700 000 000', 'string', 'Main hospital phone number', true],
            'hospital_email' => ['info@duncohms.co.ke', 'string', 'Hospital email address', true],
            'hospital_website' => ['https://duncohms.co.ke', 'string', 'Hospital website URL', true],

            // Emergency contacts
            'emergency_phone_1' => ['+254 700 000 000', 'string', 'Primary emergency phone', true],
            'ambulance_phone' => ['+254 700 000 002', 'string', 'Ambulance dispatch phone', true],
            'admin_mobile' => ['+254 700 000 010', 'string', 'Admin mobile number', true],
            'ict_mobile' => ['+254 700 000 011', 'string', 'ICT support mobile number', true],

            // Finance
            'currency' => ['KES', 'string', 'Currency code', true],
            'currency_symbol' => ['KES', 'string', 'Currency symbol for display', true],
            'currency_position' => ['before', 'string', 'Currency position (before/after amount)', true],

            // Documents
            'document_footer' => ['This is a computer-generated document.', 'string', 'Default footer text for documents', true],
            'document_watermark' => ['', 'string', 'Watermark text for documents', true],
            'tax_rate' => ['16', 'number', 'Default tax rate percentage', true],
            'tax_name' => ['VAT', 'string', 'Tax label name', true],

            // Display
            'date_format' => ['M d, Y', 'string', 'Date display format', true],
            'time_format' => ['h:i A', 'string', 'Time display format', true],
            'timezone' => ['Africa/Nairobi', 'string', 'System timezone', true],
            'pagination_limit' => ['20', 'number', 'Default items per page', true],

            // System
            'system_name' => ['DuncoHMS', 'string', 'System internal name', true],
            'system_developer' => ['Dunco Technologies', 'string', 'System developer name', true],
            'dark_mode' => [false, 'boolean', 'Dark mode enabled', true],

            // Logo & favicon (empty by default - user uploads via admin)
            'hospital_logo' => ['', 'string', 'Hospital logo (base64 or file path)', true],
            'favicon' => ['', 'string', 'Favicon (base64 or file path)', true],
        ];

        foreach ($settings as $key => [$value, $type, $description, $isPublic]) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $type,
                    'description' => $description,
                    'is_public' => $isPublic,
                ]
            );
        }
    }
}
