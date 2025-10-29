<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocalizationController extends Controller
{
    public function index(): View
    {
        $supportedLanguages = [
            'en' => ['name' => 'English', 'flag' => '🇬🇧'],
            'ar' => ['name' => 'العربية', 'flag' => '🇸🇦'],
            'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪'],
            'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
            'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
            'it' => ['name' => 'Italiano', 'flag' => '🇮🇹'],
            'pt' => ['name' => 'Português', 'flag' => '🇵🇹'],
            'ru' => ['name' => 'Русский', 'flag' => '🇷🇺'],
            'tr' => ['name' => 'Türkçe', 'flag' => '🇹🇷'],
            'zh' => ['name' => '中文', 'flag' => '🇨🇳'],
            'sw' => ['name' => 'Kiswahili', 'flag' => '🇰🇪'],
        ];
        
        $currentLocale = SystemSetting::get('default_locale', config('app.locale', 'en'));
        $dateFormat = SystemSetting::get('date_format', 'Y-m-d');
        $timeFormat = SystemSetting::get('time_format', 'H:i');
        $timezone = SystemSetting::get('timezone', config('app.timezone', 'UTC'));
        
        return view('hms.settings.localization', compact(
            'supportedLanguages',
            'currentLocale',
            'dateFormat',
            'timeFormat',
            'timezone'
        ));
    }
    
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_locale' => 'required|string|max:10',
            'date_format' => 'required|string|max:20',
            'time_format' => 'required|string|max:20',
            'timezone' => 'required|string|max:100',
        ]);
        
        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value);
        }
        
        // Update app locale if changed
        if ($validated['default_locale'] !== config('app.locale')) {
            SystemSetting::set('default_locale', $validated['default_locale']);
        }
        
        return redirect()->route('hms.system.localization')
            ->with('success', 'Localization settings updated successfully.');
    }
}
