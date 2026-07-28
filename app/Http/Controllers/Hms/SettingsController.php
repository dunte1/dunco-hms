<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\HospitalBranch;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function backup(): View
    {
        $backups = [];
        $backupPath = storage_path('app/backups');
        
        if (File::exists($backupPath)) {
            $files = File::files($backupPath);
            foreach ($files as $file) {
                if (Str::endsWith($file->getFilename(), '.sql')) {
                    $backups[] = [
                        'name' => $file->getFilename(),
                        'size' => $file->getSize(),
                        'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }
            // Sort by creation time, newest first
            usort($backups, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }
        
        $lastBackup = count($backups) > 0 ? $backups[0]['created_at'] : 'Not yet created';
        
        return view('hms.settings.backup', compact('backups', 'lastBackup'));
    }

    public function createBackup(): RedirectResponse
    {
        try {
            // Ensure backup directory exists
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            // Generate backup filename
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $filepath = $backupPath . '/' . $filename;
            
            // Get database config
            $database = config('database.connections.' . config('database.default'));
            $driver = $database['driver'] ?? 'sqlite';
            
            // For SQLite (default in this project)
            if ($driver === 'sqlite') {
                $dbPath = $database['database'];
                // Handle both absolute paths and relative paths
                if (!File::exists($dbPath)) {
                    $dbPath = database_path($database['database']);
                }
                
                if (File::exists($dbPath)) {
                    File::copy($dbPath, $filepath);
                } else {
                    return redirect()->route('hms.settings.backup')
                        ->with('error', 'Database file not found at: ' . $dbPath);
                }
            } else {
                // For MySQL/PostgreSQL
                $host = $database['host'] ?? '127.0.0.1';
                $username = $database['username'] ?? 'root';
                $password = $database['password'] ?? '';
                $databaseName = $database['database'] ?? '';
                
                if ($driver === 'mysql') {
                    $command = sprintf(
                        'mysqldump -h %s -u %s -p%s %s > %s',
                        escapeshellarg($host),
                        escapeshellarg($username),
                        escapeshellarg($password),
                        escapeshellarg($databaseName),
                        escapeshellarg($filepath)
                    );
                } elseif ($driver === 'pgsql') {
                    $port = $database['port'] ?? '5432';
                    putenv("PGPASSWORD=" . $password);
                    $command = sprintf(
                        'pg_dump -h %s -p %s -U %s %s > %s',
                        escapeshellarg($host),
                        escapeshellarg($port),
                        escapeshellarg($username),
                        escapeshellarg($databaseName),
                        escapeshellarg($filepath)
                    );
                } else {
                    return redirect()->route('hms.settings.backup')
                        ->with('error', 'Unsupported database driver: ' . $driver);
                }
                
                exec($command, $output, $returnVar);
                
                if ($returnVar !== 0) {
                    return redirect()->route('hms.settings.backup')
                        ->with('error', 'Backup creation failed. Please check database credentials. Error: ' . implode(' ', $output));
                }
            }
            
            // Log the backup creation
            AuditLog::create([
                'user_type' => 'App\Models\User',
                'user_id' => auth()->id(),
                'action' => 'backup_created',
                'model_type' => 'System',
                'model_id' => null,
                'description' => "Database backup created: {$filename}",
                'ip_address' => request()->ip(),
            ]);
            
            return redirect()->route('hms.settings.backup')
                ->with('success', 'Backup created successfully! File: ' . $filename);
                
        } catch (\Exception $e) {
            return redirect()->route('hms.settings.backup')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }
    
    public function restoreBackup(Request $request): RedirectResponse
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt|max:102400', // 100MB max
        ]);
        
        try {
            $file = $request->file('backup_file');
            $backupPath = storage_path('app/backups');
            
            // Save uploaded file temporarily
            $tempPath = $file->storeAs('temp', 'restore_' . time() . '.sql');
            $fullPath = storage_path('app/' . $tempPath);
            
            // Get database config
            $database = config('database.connections.' . config('database.default'));
            $driver = $database['driver'] ?? 'sqlite';
            
            if ($driver === 'sqlite') {
                // For SQLite, restore by replacing the database file
                $dbPath = $database['database'];
                // Handle both absolute paths and relative paths
                if (!File::exists($dbPath)) {
                    // Try database_path for relative paths
                    $relativePath = database_path($database['database']);
                    if (File::exists($relativePath)) {
                        $dbPath = $relativePath;
                    }
                }
                
                // Make sure directory exists
                $dbDir = dirname($dbPath);
                if (!File::exists($dbDir)) {
                    File::makeDirectory($dbDir, 0755, true);
                }
                
                File::copy($fullPath, $dbPath);
                File::chmod($dbPath, 0666);
            } else {
                // For MySQL/PostgreSQL
                $host = $database['host'] ?? '127.0.0.1';
                $username = $database['username'] ?? 'root';
                $password = $database['password'] ?? '';
                $databaseName = $database['database'] ?? '';
                
                if ($driver === 'mysql') {
                    $command = sprintf(
                        'mysql -h %s -u %s -p%s %s < %s',
                        escapeshellarg($host),
                        escapeshellarg($username),
                        escapeshellarg($password),
                        escapeshellarg($databaseName),
                        escapeshellarg($fullPath)
                    );
                } elseif ($driver === 'pgsql') {
                    $port = $database['port'] ?? '5432';
                    putenv("PGPASSWORD=" . $password);
                    $command = sprintf(
                        'psql -h %s -p %s -U %s -d %s < %s',
                        escapeshellarg($host),
                        escapeshellarg($port),
                        escapeshellarg($username),
                        escapeshellarg($databaseName),
                        escapeshellarg($fullPath)
                    );
                } else {
                    File::delete($fullPath);
                    return redirect()->route('hms.settings.backup')
                        ->with('error', 'Unsupported database driver: ' . $driver);
                }
                
                exec($command, $output, $returnVar);
                
                if ($returnVar !== 0) {
                    File::delete($fullPath);
                    return redirect()->route('hms.settings.backup')
                        ->with('error', 'Restore failed. Please check the backup file format. Error: ' . implode(' ', $output));
                }
            }
            
            // Clean up temp file
            File::delete($fullPath);
            
            // Log the restore
            AuditLog::create([
                'user_type' => 'App\Models\User',
                'user_id' => auth()->id(),
                'action' => 'backup_restored',
                'model_type' => 'System',
                'model_id' => null,
                'description' => "Database restored from: {$file->getClientOriginalName()}",
                'ip_address' => request()->ip(),
            ]);
            
            return redirect()->route('hms.settings.backup')
                ->with('success', 'Database restored successfully from: ' . $file->getClientOriginalName());
                
        } catch (\Exception $e) {
            return redirect()->route('hms.settings.backup')
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }
    
    public function downloadBackup(string $filename): Response
    {
        $filepath = storage_path('app/backups/' . basename($filename));
        
        if (!File::exists($filepath)) {
            abort(404, 'Backup file not found');
        }
        
        return Response::download($filepath);
    }
}