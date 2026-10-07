<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\BackupRecord;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class BackupService
{
    public const DISK = 'backups';

    /**
     * Run a Spatie backup and return a structured result.
     *
     * Note: Spatie does not fire Laravel events when notifications are disabled,
     * so BackupRecord rows are written here (and via syncMissingRecords) rather
     * than relying only on the event listener.
     */
    public function create(bool $manual = true, ?string $notes = null): array
    {
        $startedAt = now();

        try {
            $this->ensureBackupDirectory();

            $exitCode = Artisan::call('backup:run', [
                '--disable-notifications' => true,
            ]);

            $output = Artisan::output();

            if ($exitCode !== 0) {
                $this->recordFailure($notes ?? ($manual ? 'Manual backup via settings' : 'Scheduled backup'), $output);

                return [
                    'success' => false,
                    'message' => 'Backup failed: ' . trim($output !== '' ? $output : 'Unknown error'),
                    'record' => null,
                ];
            }

            $record = BackupRecord::query()
                ->where('created_at', '>=', $startedAt->copy()->subSecond())
                ->whereIn('status', ['completed', 'running'])
                ->latest('created_at')
                ->first();

            if (! $record) {
                $record = $this->recordFromNewestArchive($notes ?? ($manual ? 'Manual backup via settings' : 'Scheduled backup'));
            } elseif ($record->status === 'running') {
                $record->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }

            // Catch archives created without events (disable-notifications path).
            $this->syncMissingRecords();

            if ($manual) {
                AuditLog::create([
                    'user_type' => 'App\Models\User',
                    'user_id' => auth()->id(),
                    'action' => 'backup_created',
                    'model_type' => 'System',
                    'model_id' => null,
                    'description' => 'Database backup created: ' . ($record->backup_location ?? 'archive'),
                    'ip_address' => request()->ip(),
                ]);
            }

            return [
                'success' => true,
                'message' => 'Backup created successfully.',
                'record' => $record,
            ];
        } catch (\Throwable $e) {
            $this->recordFailure($notes ?? 'Backup exception', $e->getMessage());

            return [
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
                'record' => null,
            ];
        }
    }

    /**
     * Create BackupRecord rows for archives that have no matching record yet.
     */
    public function syncMissingRecords(): int
    {
        $created = 0;

        foreach ($this->archives() as $archive) {
            $exists = BackupRecord::query()
                ->where(function ($query) use ($archive) {
                    $query->where('backup_location', $archive['path'])
                        ->orWhere('backup_location', 'like', '%' . $archive['name'] . '%');
                })
                ->exists();

            if ($exists) {
                continue;
            }

            BackupRecord::create([
                'backup_type' => 'full',
                'backup_location' => $archive['path'],
                'file_size_mb' => round($archive['size'] / 1048576, 2),
                'started_at' => \Carbon\Carbon::parse($archive['created_at'])->copy()->subMinutes(2),
                'completed_at' => \Carbon\Carbon::parse($archive['created_at']),
                'status' => 'completed',
                'verified' => false,
                'notes' => 'Synced from backup archive (no Spatie event record)',
                'created_at' => \Carbon\Carbon::parse($archive['created_at']),
            ]);

            $created++;
        }

        return $created;
    }

    /**
     * List backup archives on the backups disk (newest first).
     *
     * @return array<int, array<string, mixed>>
     */
    public function archives(): array
    {
        $this->ensureBackupDirectory();

        $archives = [];
        $files = Storage::disk(self::DISK)->allFiles();

        foreach ($files as $file) {
            $basename = basename($file);
            $extension = strtolower(pathinfo($basename, PATHINFO_EXTENSION));

            if (! in_array($extension, ['zip', 'sql', 'gz', 'txt'], true)) {
                continue;
            }

            if (str_contains($file, 'backup-temp') || str_starts_with($basename, 'pre_restore_')) {
                continue;
            }

            $archives[] = [
                'name' => $basename,
                'path' => $file,
                'size' => (int) Storage::disk(self::DISK)->size($file),
                'created_at' => date('Y-m-d H:i:s', Storage::disk(self::DISK)->lastModified($file)),
                'type' => $extension,
                'encrypted' => $extension === 'zip' && ! empty(config('backup.backup.password')),
            ];
        }

        usort($archives, function (array $a, array $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });

        return $archives;
    }

    /**
     * @return \Illuminate\Support\Collection<int, BackupRecord>
     */
    public function records(int $limit = 20)
    {
        return BackupRecord::orderByDesc('created_at')->limit($limit)->get();
    }

    public function stats(): array
    {
        $archives = $this->archives();
        $totalBytes = array_sum(array_column($archives, 'size'));

        $lastBackup = $archives[0]['created_at'] ?? null;

        return [
            'archive_count' => count($archives),
            'total_size_mb' => round($totalBytes / 1048576, 2),
            'last_backup_at' => $lastBackup,
            'encrypted' => ! empty(config('backup.backup.password')),
            'disk' => self::DISK,
            'path' => storage_path('app/backups'),
            'schedule' => (string) env('BACKUP_SCHEDULE_TIME', '02:00'),
            'schedule_enabled' => (bool) env('BACKUP_SCHEDULE_ENABLED', true),
            'retention_days' => (int) env('BACKUP_KEEP_ALL_DAYS', 7),
        ];
    }

    public function downloadPath(string $filename): ?string
    {
        $relative = $this->resolveArchivePath($filename);

        if ($relative === null) {
            return null;
        }

        return Storage::disk(self::DISK)->path($relative);
    }

    public function delete(string $filename): bool
    {
        $relative = $this->resolveArchivePath($filename);

        if ($relative === null) {
            return false;
        }

        $basename = basename($relative);
        $deleted = Storage::disk(self::DISK)->delete($relative);

        BackupRecord::where('backup_location', 'like', '%' . $basename . '%')
            ->orWhere('backup_location', $relative)
            ->delete();

        return $deleted;
    }

    public function verify(string $filename): array
    {
        $relative = $this->resolveArchivePath($filename);

        if ($relative === null) {
            return [
                'success' => false,
                'message' => 'Backup file not found.',
            ];
        }

        $absolute = Storage::disk(self::DISK)->path($relative);
        $basename = basename($relative);

        if (! File::exists($absolute) || File::size($absolute) <= 0) {
            return [
                'success' => false,
                'message' => 'Backup file is missing or empty.',
            ];
        }

        $extension = strtolower(pathinfo($basename, PATHINFO_EXTENSION));

        if ($extension === 'zip') {
            $zip = new ZipArchive();
            $opened = $zip->open($absolute);

            if ($opened !== true) {
                return [
                    'success' => false,
                    'message' => 'Backup archive could not be opened (it may be encrypted with an unknown password or corrupted).',
                ];
            }

            $hasContent = $zip->numFiles > 0;
            $zip->close();

            if (! $hasContent) {
                return [
                    'success' => false,
                    'message' => 'Backup archive is empty.',
                ];
            }
        } elseif ($extension === 'sql') {
            $head = $this->readHead($absolute, 64);
            $isSqliteBinary = str_starts_with($head, "SQLite format 3\0");
            $looksLikeSql = (bool) preg_match('/\b(CREATE|INSERT|DROP|PRAGMA|BEGIN|COMMIT)\b/i', $head);

            if (! $isSqliteBinary && ! $looksLikeSql && File::size($absolute) < 32) {
                return [
                    'success' => false,
                    'message' => 'Backup file does not look like a valid database dump.',
                ];
            }
        }

        $basenameForRecord = $basename;
        BackupRecord::query()
            ->where(function ($query) use ($basenameForRecord, $relative) {
                $query->where('backup_location', 'like', '%' . $basenameForRecord . '%')
                    ->orWhere('backup_location', $relative);
            })
            ->update([
                'verified' => true,
                'verified_at' => now(),
            ]);

        AuditLog::create([
            'user_type' => 'App\Models\User',
            'user_id' => auth()->id(),
            'action' => 'backup_verified',
            'model_type' => 'System',
            'model_id' => null,
            'description' => "Backup verified: {$basename}",
            'ip_address' => request()->ip(),
        ]);

        return [
            'success' => true,
            'message' => 'Backup verified successfully.',
        ];
    }

    /**
     * Restore from an uploaded .sql dump or a Spatie .zip archive.
     */
    public function restore(UploadedFile $uploaded): array
    {
        $this->ensureBackupDirectory();

        $originalName = $uploaded->getClientOriginalName();
        $extension = strtolower($uploaded->getClientOriginalExtension());

        if (! in_array($extension, ['sql', 'txt', 'zip'], true)) {
            return [
                'success' => false,
                'message' => 'Unsupported backup format. Upload a .sql dump or a Spatie .zip archive.',
            ];
        }

        $tempDir = storage_path('app/temp');
        if (! File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $tempPath = $tempDir . '/restore_' . uniqid('', true) . '.' . $extension;
        $uploaded->move($tempDir, basename($tempPath));

        $sqlPath = $tempPath;
        $cleanup = [$tempPath];

        try {
            $this->createSafetySnapshot();

            if ($extension === 'zip') {
                $sqlPath = $this->extractSqlFromZip($tempPath);
                $cleanup[] = $sqlPath;
            }

            $this->importSqlDump($sqlPath);

            AuditLog::create([
                'user_type' => 'App\Models\User',
                'user_id' => auth()->id(),
                'action' => 'backup_restored',
                'model_type' => 'System',
                'model_id' => null,
                'description' => "Database restored from: {$originalName}",
                'ip_address' => request()->ip(),
            ]);

            return [
                'success' => true,
                'message' => "Database restored successfully from: {$originalName}",
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Restore failed: ' . $e->getMessage(),
            ];
        } finally {
            foreach ($cleanup as $file) {
                if (is_string($file) && File::exists($file)) {
                    File::delete($file);
                }
            }
        }
    }

    public function ensureBackupDirectory(): void
    {
        $path = storage_path('app/backups');

        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    protected function resolveArchivePath(string $filename): ?string
    {
        $basename = basename($filename);

        if ($basename === '' || $basename === '.' || $basename === '..') {
            return null;
        }

        foreach (Storage::disk(self::DISK)->allFiles() as $file) {
            if (basename($file) === $basename) {
                return $file;
            }
        }

        return null;
    }

    protected function recordFailure(string $notes, string $details): void
    {
        BackupRecord::create([
            'backup_type' => 'full',
            'backup_location' => 'failed',
            'file_size_mb' => null,
            'started_at' => now(),
            'completed_at' => now(),
            'status' => 'failed',
            'verified' => false,
            'notes' => Str::limit($notes . ' — ' . $details, 1000),
            'created_at' => now(),
        ]);
    }

    protected function recordFromNewestArchive(string $notes): BackupRecord
    {
        $archives = $this->archives();
        $newest = $archives[0] ?? null;

        return BackupRecord::create([
            'backup_type' => 'full',
            'backup_location' => $newest['path'] ?? 'unknown',
            'file_size_mb' => $newest ? round($newest['size'] / 1048576, 2) : null,
            'started_at' => now()->subMinute(),
            'completed_at' => now(),
            'status' => 'completed',
            'verified' => false,
            'notes' => $notes,
            'created_at' => now(),
        ]);
    }

    protected function createSafetySnapshot(): void
    {
        try {
            $database = config('database.connections.' . config('database.default'));
            $driver = $database['driver'] ?? 'sqlite';

            if ($driver !== 'sqlite') {
                return;
            }

            $dbPath = $this->resolveSqlitePath($database);

            if ($dbPath && File::exists($dbPath)) {
                $snapshot = storage_path('app/backups/pre_restore_' . date('Y-m-d_His') . '.sql');
                File::copy($dbPath, $snapshot);
            }
        } catch (\Throwable) {
            // Safety snapshot is best-effort.
        }
    }

    protected function extractSqlFromZip(string $zipPath): string
    {
        $password = config('backup.backup.password');

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new RuntimeException('Unable to open backup archive.');
        }

        if (! empty($password)) {
            $zip->setPassword($password);
        }

        $sqlPath = null;

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);

            if ($name === false || ! str_ends_with(strtolower($name), '.sql')) {
                continue;
            }

            $stream = $zip->getStream($name);

            if ($stream === false && ! empty($password)) {
                $zip->close();
                $zip = new ZipArchive();
                $zip->open($zipPath);
                $zip->setPassword($password);
                $stream = $zip->getStream($name);
            }

            if ($stream === false) {
                $zip->close();
                throw new RuntimeException('Unable to read database dump from archive. If the backup is encrypted, ensure BACKUP_ARCHIVE_PASSWORD is set correctly.');
            }

            $sqlPath = tempnam(sys_get_temp_dir(), 'backup_sql_') . '.sql';
            file_put_contents($sqlPath, stream_get_contents($stream));
            fclose($stream);
            break;
        }

        $zip->close();

        if ($sqlPath === null) {
            throw new RuntimeException('No database dump (.sql) found inside the backup archive.');
        }

        return $sqlPath;
    }

    protected function importSqlDump(string $path): void
    {
        $database = config('database.connections.' . config('database.default'));
        $driver = $database['driver'] ?? 'sqlite';
        $head = $this->readHead($path, 64);
        $isSqliteBinary = str_starts_with($head, "SQLite format 3\0");

        if ($driver === 'sqlite') {
            $dbPath = $this->resolveSqlitePath($database);

            if (! $dbPath) {
                throw new RuntimeException('SQLite database path could not be resolved.');
            }

            $dbDir = dirname($dbPath);
            if (! File::exists($dbDir)) {
                File::makeDirectory($dbDir, 0755, true);
            }

            if ($isSqliteBinary) {
                File::copy($path, $dbPath);
                File::chmod($dbPath, 0666);

                return;
            }

            $pdo = new \PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $sql = File::get($path);
            $pdo->exec($sql);

            return;
        }

        if ($isSqliteBinary) {
            throw new RuntimeException('This backup is a SQLite database file but the current connection is not SQLite.');
        }

        $host = $database['host'] ?? '127.0.0.1';
        $username = $database['username'] ?? 'root';
        $password = $database['password'] ?? '';
        $databaseName = $database['database'] ?? '';
        $cnf = null;

        if ($driver === 'mysql') {
            $cnf = tempnam(sys_get_temp_dir(), 'mycnf_');
            file_put_contents($cnf, "[client]\nhost={$host}\nuser={$username}\npassword={$password}\n");
            @chmod($cnf, 0600);
            $command = sprintf(
                'mysql --defaults-extra-file=%s %s < %s',
                escapeshellarg($cnf),
                escapeshellarg($databaseName),
                escapeshellarg($path)
            );
        } elseif ($driver === 'pgsql') {
            $port = $database['port'] ?? '5432';
            putenv('PGPASSWORD=' . $password);
            $command = sprintf(
                'psql -h %s -p %s -U %s -d %s < %s',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($databaseName),
                escapeshellarg($path)
            );
        } else {
            throw new RuntimeException('Unsupported database driver: ' . $driver);
        }

        $output = [];
        $returnVar = 0;
        exec($command, $output, $returnVar);

        if (! empty($cnf) && is_file($cnf)) {
            @unlink($cnf);
        }

        if ($returnVar !== 0) {
            throw new RuntimeException('Database import failed: ' . implode(' ', $output));
        }
    }

    protected function readHead(string $path, int $bytes = 64): string
    {
        if (! is_file($path)) {
            return '';
        }

        $handle = fopen($path, 'rb');

        if ($handle === false) {
            return '';
        }

        $head = (string) fread($handle, $bytes);
        fclose($handle);

        return $head;
    }

    protected function resolveSqlitePath(array $database): ?string
    {
        $dbPath = $database['database'] ?? null;

        if (! $dbPath) {
            return null;
        }

        if (File::exists($dbPath)) {
            return $dbPath;
        }

        $relative = database_path($dbPath);

        return File::exists($relative) ? $relative : $dbPath;
    }
}
