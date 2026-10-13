<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Hms\SystemSettingsController;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ThemeController extends Controller
{
    /**
     * Display theme page — consolidated to System Settings Theme & Branding.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('hms.system.theme');
    }

    /**
     * Update theme settings — delegate to SystemSettingsController.
     */
    public function update(Request $request): RedirectResponse
    {
        return app(SystemSettingsController::class)->updateTheme($request);
    }

    /**
     * Preview theme (legacy).
     */
    public function preview(Request $request)
    {
        return redirect()->route('hms.system.theme');
    }

    /**
     * Reset to default theme (legacy).
     */
    public function reset(): RedirectResponse
    {
        \App\Models\SystemSetting::where('key', 'theme_settings')->delete();

        return redirect()
            ->route('hms.system.theme')
            ->with('status', 'Legacy theme settings cleared. Save colors on Theme & Branding.');
    }

    /**
     * Export theme JSON (legacy).
     */
    public function export()
    {
        $theme = $this->getCurrentTheme();

        return response()->json($theme)
            ->header('Content-Disposition', 'attachment; filename="theme-' . now()->format('Y-m-d') . '.json"');
    }

    /**
     * Import theme JSON (legacy).
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'theme_file' => 'required|file|mimes:json',
        ]);

        $content = file_get_contents($request->file('theme_file')->getRealPath());
        $theme = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['error' => 'Invalid theme file format']);
        }

        \App\Models\SystemSetting::updateOrCreate(
            ['key' => 'theme_settings'],
            ['value' => json_encode($theme)]
        );

        return redirect()
            ->route('hms.system.theme')
            ->with('success', 'Theme imported (legacy key). Apply colors on Theme & Branding if needed.');
    }

    /**
     * Get current theme settings (legacy JSON key).
     */
    private function getCurrentTheme(): array
    {
        $settings = \App\Models\SystemSetting::where('key', 'theme_settings')->first();

        if ($settings) {
            return json_decode($settings->value, true) ?: [];
        }

        return [
            'primary_color' => '#10b981',
            'secondary_color' => '#3b82f6',
            'accent_color' => '#f59e0b',
            'font_family' => 'Inter',
            'dark_mode' => false,
            'sidebar_style' => 'default',
        ];
    }

    /**
     * Toggle dark mode (legacy API used by some UIs).
     */
    public function toggleDarkMode(Request $request)
    {
        $theme = $this->getCurrentTheme();
        $theme['dark_mode'] = !($theme['dark_mode'] ?? false);

        \App\Models\SystemSetting::updateOrCreate(
            ['key' => 'theme_settings'],
            ['value' => json_encode($theme)]
        );

        // Also sync primary settings dark_mode used by layouts
        \App\Models\SystemSetting::set('dark_mode', $theme['dark_mode'], 'boolean');

        return response()->json([
            'success' => true,
            'dark_mode' => $theme['dark_mode'],
        ]);
    }
}
