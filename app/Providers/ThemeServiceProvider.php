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
                    'themeSettings' => [
                        'primary_color' => SystemSetting::get('primary_color', '#6366f1'),
                        'secondary_color' => SystemSetting::get('secondary_color', '#8b5cf6'),
                        'hospital_logo' => SystemSetting::get('hospital_logo', ''),
                        'favicon' => SystemSetting::get('favicon', ''),
                        'dark_mode' => SystemSetting::get('dark_mode', false),
                        'system_name' => SystemSetting::get('system_name', 'DuncoHMS'),
                        'system_developer' => SystemSetting::get('system_developer', 'Dunco Technologies'),
                        'hospital_name' => SystemSetting::get('hospital_name', config('app.name', 'Dunco Hospital')),
                        'hospital_address' => SystemSetting::get('hospital_address', ''),
                        'hospital_phone' => SystemSetting::get('hospital_phone', ''),
                        'hospital_email' => SystemSetting::get('hospital_email', ''),
                    ]
                ]);
            } catch (\Exception $e) {
                // Fallback if database is not available
                $view->with([
                    'themeSettings' => [
                        'primary_color' => '#6366f1',
                        'secondary_color' => '#8b5cf6',
                        'hospital_logo' => '',
                        'favicon' => '',
                        'dark_mode' => false,
                        'system_name' => 'DuncoHMS',
                        'system_developer' => 'Dunco Technologies',
                        'hospital_name' => config('app.name', 'Dunco Hospital'),
                        'hospital_address' => '',
                        'hospital_phone' => '',
                        'hospital_email' => '',
                    ]
                ]);
            }
        });
    }
}
