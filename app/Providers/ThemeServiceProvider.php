<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share theme settings with all views
        View::composer('*', function ($view) {
            try {
                $view->with([
                    'themeSettings' => self::getThemeSettings()
                ]);
            } catch (\Exception $e) {
                $view->with([
                    'themeSettings' => self::getDefaultSettings()
                ]);
            }
        });
    }

    /**
     * Get all theme/branding settings from database
     */
    public static function getThemeSettings(): array
    {
        return [
            // Branding
            'primary_color' => SystemSetting::get('primary_color', '#000075'),
            'secondary_color' => SystemSetting::get('secondary_color', '#00001A'),
            'accent_color' => SystemSetting::get('accent_color', '#000075'),
            'text_color' => SystemSetting::get('text_color', '#333333'),
            'hospital_logo' => SystemSetting::get('hospital_logo', ''),
            'favicon' => SystemSetting::get('favicon', ''),
            'dark_mode' => SystemSetting::get('dark_mode', false),

            // Sidebar Colors
            'sidebar_bg' => SystemSetting::get('sidebar_bg', '#00001A'),
            'sidebar_text' => SystemSetting::get('sidebar_text', 'rgba(255,255,255,0.75)'),
            'sidebar_muted' => SystemSetting::get('sidebar_muted', 'rgba(255,255,255,0.5)'),
            'sidebar_hover' => SystemSetting::get('sidebar_hover', 'rgba(255,255,255,0.09)'),

            // UI Colors
            'navbar_border' => SystemSetting::get('navbar_border', '#E5ECEB'),
            'main_bg' => SystemSetting::get('main_bg', '#F7F9FA'),

            // System
            'system_name' => SystemSetting::get('system_name', 'DuncoHMS'),
            'system_developer' => SystemSetting::get('system_developer', 'Dunco Technologies'),

            // Hospital Identity
            'hospital_name' => SystemSetting::get('hospital_name', config('app.name', 'Dunco Hospital')),
            'hospital_short_name' => SystemSetting::get('hospital_short_name', ''),
            'hospital_address' => SystemSetting::get('hospital_address', ''),
            'hospital_phone' => SystemSetting::get('hospital_phone', ''),
            'hospital_email' => SystemSetting::get('hospital_email', ''),
            'hospital_website' => SystemSetting::get('hospital_website', ''),

            // Emergency Contacts
            'emergency_phone' => SystemSetting::get('emergency_phone_1', ''),
            'ambulance_phone' => SystemSetting::get('ambulance_phone', ''),

            // Finance
            'currency' => SystemSetting::get('currency', 'KES'),
            'currency_symbol' => SystemSetting::get('currency_symbol', 'KES'),
            'currency_position' => SystemSetting::get('currency_position', 'before'),

            // Document Settings
            'document_footer' => SystemSetting::get('document_footer', ''),
            'document_watermark' => SystemSetting::get('document_watermark', ''),
            'tax_rate' => SystemSetting::get('tax_rate', 0),
            'tax_name' => SystemSetting::get('tax_name', 'VAT'),

            // Display
            'date_format' => SystemSetting::get('date_format', 'M d, Y'),
            'time_format' => SystemSetting::get('time_format', 'h:i A'),
            'timezone' => SystemSetting::get('timezone', 'Africa/Nairobi'),
            'pagination_limit' => SystemSetting::get('pagination_limit', 20),
        ];
    }

    /**
     * Get default fallback settings
     */
    public static function getDefaultSettings(): array
    {
        return [
            'primary_color' => '#2563EB',
            'secondary_color' => '#1D4ED8',
            'accent_color' => '#2563EB',
            'text_color' => '#333333',
            'hospital_logo' => '',
            'favicon' => '',
            'dark_mode' => false,
            'system_name' => 'DuncoHMS',
            'system_developer' => 'Dunco Technologies',
            'hospital_name' => config('app.name', 'Dunco Hospital'),
            'hospital_short_name' => '',
            'hospital_address' => '',
            'hospital_phone' => '',
            'hospital_email' => '',
            'hospital_website' => '',
            'emergency_phone' => '',
            'ambulance_phone' => '',
            'currency' => 'KES',
            'currency_symbol' => 'KES',
            'currency_position' => 'before',
            'document_footer' => '',
            'document_watermark' => '',
            'tax_rate' => 0,
            'tax_name' => 'VAT',
            'date_format' => 'M d, Y',
            'time_format' => 'h:i A',
            'timezone' => 'Africa/Nairobi',
            'pagination_limit' => 20,
        ];
    }
}
