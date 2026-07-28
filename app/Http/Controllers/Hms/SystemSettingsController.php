<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\ConfigurationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SystemSettingsController extends Controller
{
    protected $configService;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
    }

    public function timezone(): View
    {
        $settings = [
            'timezone' => SystemSetting::get('timezone', 'UTC'),
            'date_format' => SystemSetting::get('date_format', 'Y-m-d'),
            'time_format' => SystemSetting::get('time_format', 'H:i:s'),
            'currency' => SystemSetting::get('currency', 'USD'),
            'currency_symbol' => SystemSetting::get('currency_symbol', '$'),
        ];
        
        return view('hms.settings.timezone', compact('settings'));
    }

    public function updateTimezone(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'currency' => 'required|string',
            'currency_symbol' => 'required|string',
        ]);

        foreach ($data as $key => $value) {
            SystemSetting::set($key, $value);
        }

        // Clear cache to immediately apply changes
        $this->configService->clearCache();
        
        // Apply timezone change immediately to current request
        config(['app.timezone' => $data['timezone']]);
        date_default_timezone_set($data['timezone']);

        return redirect()->route('hms.system.timezone')->with('status', 'Timezone settings updated successfully and applied immediately!');
    }

    public function theme(): View
    {
        $settings = [
            'primary_color' => SystemSetting::get('primary_color', '#6366f1'),
            'secondary_color' => SystemSetting::get('secondary_color', '#8b5cf6'),
            'hospital_logo' => SystemSetting::get('hospital_logo', ''),
            'favicon' => SystemSetting::get('favicon', ''),
            'dark_mode' => SystemSetting::get('dark_mode', false),
        ];
        
        return view('hms.settings.theme', compact('settings'));
    }

    public function updateTheme(Request $request): RedirectResponse
    {
        // Handle JSON requests (for dark mode toggle)
        if ($request->isJson()) {
            $data = $request->validate([
                'primary_color' => 'nullable|string',
                'secondary_color' => 'nullable|string',
                'dark_mode' => 'nullable|boolean',
            ]);

            foreach ($data as $key => $value) {
                SystemSetting::set($key, $value, $key === 'dark_mode' ? 'boolean' : 'string');
            }

            $this->configService->clearCache();
            
            return response()->json(['success' => true]);
        }

        // Handle form requests
        $data = $request->validate([
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'dark_mode' => 'nullable|in:0,1,true,false',
            'hospital_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,jpg,png,ico|max:2048',
        ]);

        $uploadedFiles = [];

        // Handle logo upload
        if ($request->hasFile('hospital_logo')) {
            try {
            // Delete old logo if exists
            $oldLogo = SystemSetting::get('hospital_logo', '');
            if ($oldLogo && str_starts_with($oldLogo, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $oldLogo);
                    Storage::disk('public')->delete($oldPath);
            }
            
            $logoPath = $request->file('hospital_logo')->store('settings/logos', 'public');
                // Ensure the URL is properly formatted
                $logoUrl = Storage::url($logoPath);
                // Normalize the URL to always start with /storage/
                if (!str_starts_with($logoUrl, '/storage/')) {
                    $logoUrl = '/storage/' . ltrim($logoUrl, '/');
                }
                SystemSetting::set('hospital_logo', $logoUrl, 'string', 'Hospital logo', false);
                $uploadedFiles[] = 'logo';
                
                // Update favicon in browser
                $this->updateFavicon($logoUrl);
                
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['hospital_logo' => 'Failed to upload logo: ' . $e->getMessage()]);
            }
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            try {
            // Delete old favicon if exists
            $oldFavicon = SystemSetting::get('favicon', '');
            if ($oldFavicon && str_starts_with($oldFavicon, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $oldFavicon);
                    Storage::disk('public')->delete($oldPath);
            }
            
            $faviconPath = $request->file('favicon')->store('settings/favicons', 'public');
                // Ensure the URL is properly formatted
                $faviconUrl = Storage::url($faviconPath);
                // Normalize the URL to always start with /storage/
                if (!str_starts_with($faviconUrl, '/storage/')) {
                    $faviconUrl = '/storage/' . ltrim($faviconUrl, '/');
                }
                SystemSetting::set('favicon', $faviconUrl, 'string', 'Favicon', false);
                $uploadedFiles[] = 'favicon';
                
                // Update favicon in browser
                $this->updateFavicon($faviconUrl);
                
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['favicon' => 'Failed to upload favicon: ' . $e->getMessage()]);
            }
        }

        // Save other settings
        foreach ($data as $key => $value) {
            if ($key !== 'hospital_logo' && $key !== 'favicon') {
                // Handle checkbox values properly
                if ($key === 'dark_mode') {
                    // Convert various checkbox values to boolean
                    $value = in_array($value, [1, '1', true, 'true', 'on']) ? true : false;
                }
                SystemSetting::set($key, $value, $key === 'dark_mode' ? 'boolean' : 'string');
            }
        }

        // Clear cache to immediately apply changes
        $this->configService->clearCache();

        $message = 'Theme settings updated successfully!';
        if (!empty($uploadedFiles)) {
            $message .= ' Successfully uploaded: ' . implode(' and ', $uploadedFiles) . '.';
        }

        return redirect()->route('hms.system.theme')->with('success', $message);
    }

    private function updateFavicon($faviconUrl)
    {
        // This method can be used to update favicon dynamically
        // For now, we'll just store it in the database
        // The favicon will be applied on next page load
    }

    public function maps(): View
    {
        $settings = [
            'google_maps_api_key' => SystemSetting::get('google_maps_api_key', ''),
            'map_latitude' => SystemSetting::get('map_latitude', -1.2921), // Default: Nairobi, Kenya
            'map_longitude' => SystemSetting::get('map_longitude', 36.8219),
            'map_zoom' => SystemSetting::get('map_zoom', 15),
            'map_type' => SystemSetting::get('map_type', 'roadmap'),
            'map_marker_color' => SystemSetting::get('map_marker_color', '#3b82f6'),
            'map_height' => SystemSetting::get('map_height', 400),
        ];
        
        return view('hms.settings.maps', compact('settings'));
    }

    public function updateMaps(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'google_maps_api_key' => 'nullable|string|max:255',
            'map_latitude' => 'required|numeric|between:-90,90',
            'map_longitude' => 'required|numeric|between:-180,180',
            'map_zoom' => 'required|integer|between:1,20',
            'map_type' => 'required|string|in:roadmap,satellite,hybrid,terrain',
            'map_marker_color' => 'nullable|string|max:7',
            'map_height' => 'nullable|integer|min:200|max:800',
        ]);

        foreach ($data as $key => $value) {
            $type = in_array($key, ['map_zoom', 'map_height', 'map_latitude', 'map_longitude']) ? 'number' : 'string';
            SystemSetting::set($key, $value, $type, 'Google Maps ' . ucwords(str_replace('_', ' ', $key)), false);
        }

        $this->configService->clearCache();

        return redirect()->route('hms.system.maps')->with('success', 'Map settings updated successfully!');
    }

    public function contactInfo(): View
    {
        $settings = [
            'contact_primary_phone' => SystemSetting::get('contact_primary_phone', SystemSetting::get('hospital_phone', '')),
            'contact_primary_email' => SystemSetting::get('contact_primary_email', SystemSetting::get('hospital_email', '')),
            'contact_primary_address' => SystemSetting::get('contact_primary_address', SystemSetting::get('hospital_address', '')),
            'contact_emergency_phone' => SystemSetting::get('contact_emergency_phone', ''),
            'contact_office_hours' => SystemSetting::get('contact_office_hours', json_encode([
                'monday' => '8:00 AM - 6:00 PM',
                'tuesday' => '8:00 AM - 6:00 PM',
                'wednesday' => '8:00 AM - 6:00 PM',
                'thursday' => '8:00 AM - 6:00 PM',
                'friday' => '8:00 AM - 6:00 PM',
                'saturday' => '9:00 AM - 2:00 PM',
                'sunday' => 'Closed',
            ])),
            'social_facebook' => SystemSetting::get('social_facebook', ''),
            'social_twitter' => SystemSetting::get('social_twitter', ''),
            'social_instagram' => SystemSetting::get('social_instagram', ''),
            'social_linkedin' => SystemSetting::get('social_linkedin', ''),
            'social_youtube' => SystemSetting::get('social_youtube', ''),
        ];
        
        return view('hms.settings.contact-info', compact('settings'));
    }

    public function updateContactInfo(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'contact_primary_phone' => 'required|string|max:50',
            'contact_primary_email' => 'required|email|max:255',
            'contact_primary_address' => 'required|string|max:500',
            'contact_emergency_phone' => 'nullable|string|max:50',
            'contact_office_hours' => 'nullable|string|max:1000',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
        ]);

        foreach ($data as $key => $value) {
            SystemSetting::set($key, $value, 'string', 'Contact Information - ' . ucwords(str_replace('_', ' ', $key)), true);
        }

        $this->configService->clearCache();

        return redirect()->route('hms.system.contact-info')->with('success', 'Contact information updated successfully!');
    }
}
