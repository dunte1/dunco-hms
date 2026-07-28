<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    /**
     * Display theme customizer
     */
    public function index(): View
    {
        $currentTheme = $this->getCurrentTheme();
        return view('hms.settings.theme-customizer', compact('currentTheme'));
    }

    /**
     * Update theme settings
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'primary_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'accent_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'font_family' => 'nullable|string',
            'dark_mode' => 'nullable|boolean',
            'sidebar_style' => 'nullable|in:default,compact,wide',
            'logo' => 'nullable|image|max:2048',
        ]);

        $themeSettings = [
            'primary_color' => $validated['primary_color'] ?? '#10b981',
            'secondary_color' => $validated['secondary_color'] ?? '#3b82f6',
            'accent_color' => $validated['accent_color'] ?? '#f59e0b',
            'font_family' => $validated['font_family'] ?? 'Inter',
            'dark_mode' => $request->has('dark_mode'),
            'sidebar_style' => $validated['sidebar_style'] ?? 'default',
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('themes', 'public');
            $themeSettings['logo'] = Storage::url($logoPath);
        }

        // Save to settings
        \App\Models\SystemSetting::updateOrCreate(
            ['key' => 'theme_settings'],
            ['value' => json_encode($themeSettings)]
        );

        return redirect()
            ->route('hms.settings.theme')
            ->with('success', 'Theme updated successfully.');
    }

    /**
     * Preview theme
     */
    public function preview(Request $request): View
    {
        $theme = $request->only([
            'primary_color', 'secondary_color', 'accent_color',
            'font_family', 'dark_mode', 'sidebar_style'
        ]);

        return view('hms.settings.theme-preview', compact('theme'));
    }

    /**
     * Reset to default theme
     */
    public function reset(): RedirectResponse
    {
        \App\Models\SystemSetting::where('key', 'theme_settings')->delete();

        return redirect()
            ->route('hms.settings.theme')
            ->with('success', 'Theme reset to default.');
    }

    /**
     * Export theme
     */
    public function export()
    {
        $theme = $this->getCurrentTheme();
        
        return response()->json($theme)
            ->header('Content-Disposition', 'attachment; filename="theme-' . now()->format('Y-m-d') . '.json"');
    }

    /**
     * Import theme
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
            ->route('hms.settings.theme')
            ->with('success', 'Theme imported successfully.');
    }

    /**
     * Get current theme settings
     */
    private function getCurrentTheme(): array
    {
        $settings = \App\Models\SystemSetting::where('key', 'theme_settings')->first();
        
        if ($settings) {
            return json_decode($settings->value, true);
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
     * Toggle dark mode
     */
    public function toggleDarkMode(Request $request)
    {
        $theme = $this->getCurrentTheme();
        $theme['dark_mode'] = !($theme['dark_mode'] ?? false);

        \App\Models\SystemSetting::updateOrCreate(
            ['key' => 'theme_settings'],
            ['value' => json_encode($theme)]
        );

        return response()->json([
            'success' => true,
            'dark_mode' => $theme['dark_mode'],
        ]);
    }
}
