<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\BackupRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index(): JsonResponse
    {
        $backups = BackupRecord::orderByDesc('started_at')->paginate(20);

        return response()->json($backups);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'backup_type' => 'required|in:full,incremental,differential',
            'backup_location' => 'required|string|max:255',
            'file_size_mb' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['started_at'] = now();
        $data['completed_at'] = now();
        $data['created_at'] = now();
        $data['status'] = 'completed';

        BackupRecord::create($data);

        return back()->with('status', 'Backup record created');
    }

    public function verify(BackupRecord $backup): RedirectResponse
    {
        $backup->update([
            'verified' => true,
            'verified_at' => now(),
        ]);

        return back()->with('status', 'Backup verified');
    }
}
