<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\SystemSetting;

class HrSettingsController extends Controller
{
    /**
     * Display HR settings
     */
    public function index(): View
    {
        $settings = [
            'working_days' => SystemSetting::get('hr_working_days', 'Monday,Tuesday,Wednesday,Thursday,Friday'),
            'working_hours_start' => SystemSetting::get('hr_working_hours_start', '08:00'),
            'working_hours_end' => SystemSetting::get('hr_working_hours_end', '17:00'),
            'salary_cycle' => SystemSetting::get('hr_salary_cycle', 'monthly'),
            'salary_day' => SystemSetting::get('hr_salary_day', '1'),
            'default_leave_days' => SystemSetting::get('hr_default_leave_days', '21'),
            'auto_id_prefix' => SystemSetting::get('hr_auto_id_prefix', 'EMP'),
            'document_expiry_reminder_days' => SystemSetting::get('hr_document_expiry_reminder_days', '30'),
        ];
        
        return view('hms.hr.settings.index', compact('settings'));
    }

    /**
     * Update HR settings
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'working_days' => 'nullable|string',
            'working_hours_start' => 'nullable|date_format:H:i',
            'working_hours_end' => 'nullable|date_format:H:i',
            'salary_cycle' => 'required|in:monthly,biweekly,weekly',
            'salary_day' => 'required|integer|min:1|max:31',
            'default_leave_days' => 'required|integer|min:0',
            'auto_id_prefix' => 'required|string|max:10',
            'document_expiry_reminder_days' => 'required|integer|min:1',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set('hr_' . $key, $value);
        }

        return redirect()->route('hms.hr.settings.index')
            ->with('success', 'HR settings updated successfully.');
    }
}

