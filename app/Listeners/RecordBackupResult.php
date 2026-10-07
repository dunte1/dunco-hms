<?php

namespace App\Listeners;

use App\Models\BackupRecord;
use Spatie\Backup\Events\BackupHasFailed;
use Spatie\Backup\Events\BackupWasSuccessful;
use Spatie\Backup\Events\BackupZipWasCreated;

class RecordBackupResult
{
    public function zipCreated(BackupZipWasCreated $event): void
    {
        $path = $event->pathToZip;
        $sizeMb = is_file($path) ? round(filesize($path) / 1048576, 2) : null;

        BackupRecord::create([
            'backup_type' => 'full',
            'backup_location' => $this->relativeLocation($path),
            'file_size_mb' => $sizeMb,
            'started_at' => now(),
            'completed_at' => null,
            'status' => 'running',
            'verified' => false,
            'notes' => 'Backup archive created',
            'created_at' => now(),
        ]);
    }

    public function successful(BackupWasSuccessful $event): void
    {
        $record = BackupRecord::query()
            ->where('status', 'running')
            ->whereNull('completed_at')
            ->latest('created_at')
            ->first();

        $path = null;
        $sizeMb = null;

        try {
            $backups = $event->backupDestination->backups();
            $newest = $backups->newest();

            if ($newest) {
                $path = $newest->path();
                $sizeMb = round($newest->sizeInBytes() / 1048576, 2);
            }
        } catch (\Throwable) {
            // Destination listing is best-effort.
        }

        if ($record) {
            $record->update([
                'status' => 'completed',
                'completed_at' => now(),
                'backup_location' => $path ? $this->relativeLocation($path) : $record->backup_location,
                'file_size_mb' => $sizeMb ?? $record->file_size_mb,
            ]);

            return;
        }

        BackupRecord::create([
            'backup_type' => 'full',
            'backup_location' => $path ? $this->relativeLocation($path) : 'unknown',
            'file_size_mb' => $sizeMb,
            'started_at' => now()->subMinute(),
            'completed_at' => now(),
            'status' => 'completed',
            'verified' => false,
            'notes' => 'Backup completed',
            'created_at' => now(),
        ]);
    }

    public function failed(BackupHasFailed $event): void
    {
        BackupRecord::create([
            'backup_type' => 'full',
            'backup_location' => 'failed',
            'file_size_mb' => null,
            'started_at' => now(),
            'completed_at' => now(),
            'status' => 'failed',
            'verified' => false,
            'notes' => 'Backup failed: ' . $event->exception->getMessage(),
            'created_at' => now(),
        ]);
    }

    protected function relativeLocation(string $path): string
    {
        $root = storage_path('app/backups');

        if (str_starts_with($path, $root)) {
            return ltrim(substr($path, strlen($root)), '/\\');
        }

        return basename($path);
    }
}
