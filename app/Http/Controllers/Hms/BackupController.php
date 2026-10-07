<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BackupRecord;
use App\Services\BackupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index(): JsonResponse
    {
        $backups = BackupRecord::orderByDesc('created_at')->paginate(20);

        return response()->json($backups);
    }

    public function store(Request $request, BackupService $backupService): RedirectResponse
    {
        $data = $request->validate([
            'backup_type' => 'nullable|in:full,incremental,differential',
            'backup_location' => 'nullable|string|max:255',
            'file_size_mb' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'run_backup' => 'nullable|boolean',
        ]);

        if ($request->boolean('run_backup')) {
            $result = $backupService->create(
                manual: true,
                notes: $data['notes'] ?? 'ICT manual backup'
            );

            return back()->with(
                $result['success'] ? 'status' : 'error',
                $result['message']
            );
        }

        $payload = array_filter([
            'backup_type' => $data['backup_type'] ?? 'full',
            'backup_location' => $data['backup_location'] ?? 'external',
            'file_size_mb' => $data['file_size_mb'] ?? null,
            'notes' => $data['notes'] ?? null,
        ], fn ($value) => $value !== null);

        $payload['started_at'] = now();
        $payload['completed_at'] = now();
        $payload['created_at'] = now();
        $payload['status'] = 'completed';

        BackupRecord::create($payload);

        return back()->with('status', 'Backup record created');
    }

    public function verify(BackupRecord $backup, BackupService $backupService): RedirectResponse
    {
        $location = (string) $backup->backup_location;
        $basename = basename($location);
        $isLocalArchive = $basename !== ''
            && ! in_array($basename, ['failed', 'external', 'unknown'], true)
            && $backupService->downloadPath($basename) !== null;

        if ($isLocalArchive) {
            $result = $backupService->verify($basename);

            if (! $result['success']) {
                return back()->with('error', $result['message']);
            }
        } else {
            $backup->update([
                'verified' => true,
                'verified_at' => now(),
                'notes' => trim(($backup->notes ? $backup->notes . ' | ' : '') . 'Manually verified (archive not on local backups disk)'),
            ]);
        }

        return back()->with('status', 'Backup verified');
    }

    public function destroy(BackupRecord $backup): RedirectResponse
    {
        $backup->delete();

        AuditLog::create([
            'user_type' => 'App\Models\User',
            'user_id' => auth()->id(),
            'action' => 'backup_record_deleted',
            'model_type' => 'BackupRecord',
            'model_id' => $backup->id,
            'description' => 'Backup record removed from ICT log',
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', 'Backup record deleted');
    }
}
