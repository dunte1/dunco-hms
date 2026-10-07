<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\HospitalBranch;
use App\Models\AuditLog;
use App\Services\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = SystemSetting::orderBy('key')->paginate(20);
        $branches = HospitalBranch::orderBy('name')->get();
        return view('hms.settings.index', compact('settings', 'branches'));
    }

    public function general(): View
    {
        $settings = [
            'system_name' => SystemSetting::get('system_name', 'DuncoHMS'),
            'system_developer' => SystemSetting::get('system_developer', 'Dunco Technologies'),
            'hospital_name' => SystemSetting::get('hospital_name', 'Dunco Hospital'),
            'hospital_address' => SystemSetting::get('hospital_address', ''),
            'hospital_phone' => SystemSetting::get('hospital_phone', ''),
            'hospital_email' => SystemSetting::get('hospital_email', ''),
            'currency' => SystemSetting::get('currency', 'USD'),
            'timezone' => SystemSetting::get('timezone', 'UTC'),
            'date_format' => SystemSetting::get('date_format', 'Y-m-d'),
            'time_format' => SystemSetting::get('time_format', 'H:i:s'),
        ];
        
        return view('hms.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'system_name' => 'required|string',
            'system_developer' => 'required|string',
            'hospital_name' => 'required|string',
            'hospital_address' => 'required|string',
            'hospital_phone' => 'required|string',
            'hospital_email' => 'required|email',
            'currency' => 'required|string',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
        ]);

        foreach ($data as $key => $value) {
            $description = match($key) {
                'system_name' => 'System/Software name',
                'system_developer' => 'System developer/copyright',
                'hospital_name' => 'Hospital name',
                default => ucwords(str_replace('_', ' ', $key)) . ' setting'
            };
            SystemSetting::set($key, $value, 'string', $description, true);
        }

        return redirect()->route('hms.settings.general')->with('status', 'General settings updated successfully');
    }

    public function emergencyContacts(): View
    {
        $contacts = [
            'emergency_phone_1' => SystemSetting::get('emergency_phone_1', '+254 700 000 000'),
            'emergency_phone_2' => SystemSetting::get('emergency_phone_2', '+254 700 000 001'),
            'emergency_email' => SystemSetting::get('emergency_email', 'emergency@duncohms.co.ke'),
            'ambulance_phone' => SystemSetting::get('ambulance_phone', '+254 700 000 002'),
            'emergency_department_phone' => SystemSetting::get('emergency_department_phone', '+254 700 000 003'),
            'hospital_phone' => SystemSetting::get('hospital_phone', '+254 700 000 000'),
            'admin_mobile' => SystemSetting::get('admin_mobile', '+254 700 000 010'),
            'ict_mobile' => SystemSetting::get('ict_mobile', '+254 700 000 011'),
        ];
        return view('hms.settings.emergency-contacts', compact('contacts'));
    }

    public function updateEmergencyContacts(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'emergency_phone_1' => 'required|string',
            'emergency_phone_2' => 'nullable|string',
            'emergency_email' => 'nullable|email',
            'ambulance_phone' => 'required|string',
            'emergency_department_phone' => 'nullable|string',
            'hospital_phone' => 'required|string',
            'admin_mobile' => 'nullable|string',
            'ict_mobile' => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            SystemSetting::set($key, $value, 'string', ucwords(str_replace('_', ' ', $key)), true);
        }

        return redirect()->route('hms.settings.emergency-contacts')->with('status', 'Emergency contacts updated successfully');
    }

    public function branches(): View
    {
        $branches = HospitalBranch::orderBy('name')->paginate(10);
        return view('hms.settings.branches', compact('branches'));
    }

    public function createBranch(): View
    {
        return view('hms.settings.create-branch');
    }

    public function storeBranch(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'branch_code' => 'required|string|unique:hospital_branches,branch_code',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'manager_name' => 'nullable|string',
            'manager_phone' => 'nullable|string',
            'manager_email' => 'nullable|email',
            'is_main_branch' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        HospitalBranch::create($data);
        return redirect()->route('hms.settings.branches')->with('status', 'Hospital branch created');
    }

    public function auditLogs(): View
    {
        $logs = AuditLog::with('user')
            ->latest()
            ->paginate(50);
        return view('hms.settings.audit-logs', compact('logs'));
    }

    public function backup(BackupService $backupService): View
    {
        $backups = $backupService->archives();
        $stats = $backupService->stats();
        $records = $backupService->records(15);

        $lastBackup = $stats['last_backup_at'] ?? 'Not yet created';

        return view('hms.settings.backup', compact('backups', 'lastBackup', 'stats', 'records'));
    }

    public function createBackup(BackupService $backupService): RedirectResponse
    {
        $result = $backupService->create(manual: true);

        if (! $result['success']) {
            return redirect()->route('hms.settings.backup')
                ->with('error', $result['message']);
        }

        return redirect()->route('hms.settings.backup')
            ->with('success', $result['message']);
    }

    public function restoreBackup(Request $request, BackupService $backupService): RedirectResponse
    {
        $request->validate([
            'backup_file' => 'required|file|max:102400',
        ]);

        $result = $backupService->restore($request->file('backup_file'));

        if (! $result['success']) {
            return redirect()->route('hms.settings.backup')
                ->with('error', $result['message']);
        }

        return redirect()->route('hms.settings.backup')
            ->with('success', $result['message']);
    }

    public function downloadBackup(string $filename, BackupService $backupService): BinaryFileResponse
    {
        $filepath = $backupService->downloadPath($filename);

        if (! $filepath || ! File::exists($filepath)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($filepath, basename($filepath));
    }

    public function deleteBackup(string $filename, BackupService $backupService): RedirectResponse
    {
        $deleted = $backupService->delete($filename);

        if (! $deleted) {
            return redirect()->route('hms.settings.backup')
                ->with('error', 'Backup file not found or could not be deleted.');
        }

        AuditLog::create([
            'user_type' => 'App\Models\User',
            'user_id' => auth()->id(),
            'action' => 'backup_deleted',
            'model_type' => 'System',
            'model_id' => null,
            'description' => "Backup deleted: " . basename($filename),
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('hms.settings.backup')
            ->with('success', 'Backup deleted successfully.');
    }

    public function verifyBackup(string $filename, BackupService $backupService): RedirectResponse
    {
        $result = $backupService->verify($filename);

        return redirect()->route('hms.settings.backup')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}