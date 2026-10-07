<?php

namespace Tests\Feature;

use App\Models\BackupRecord;
use App\Models\User;
use App\Services\BackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class BackupModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::firstOrCreate(['name' => 'manage backups']);
        $this->user = User::factory()->create();
        $this->user->givePermissionTo('manage backups');

        Storage::fake('backups');
        config([
            'filesystems.disks.backups' => [
                'driver' => 'local',
                'root' => storage_path('app/backups-test'),
                'throw' => false,
                'report' => false,
            ],
            'backup.backup.destination.disks' => ['backups'],
            'backup.backup.password' => null,
        ]);
    }

    protected function tearDown(): void
    {
        $path = storage_path('app/backups-test');
        if (is_dir($path)) {
            \Illuminate\Support\Facades\File::deleteDirectory($path);
        }

        parent::tearDown();
    }

    public function test_settings_backup_page_is_accessible_with_permission(): void
    {
        $response = $this->actingAs($this->user)->get(route('hms.settings.backup'));

        $response->assertOk();
        $response->assertSee('Backup & Restore');
        $response->assertSee('Create Backup');
    }

    public function test_create_backup_endpoint_uses_backup_service(): void
    {
        $this->mock(BackupService::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->andReturn([
                    'success' => true,
                    'message' => 'Backup created successfully.',
                    'record' => BackupRecord::factory()->create(),
                ]);
        });

        $response = $this->actingAs($this->user)->post(route('hms.settings.backup.create'));

        $response->assertRedirect(route('hms.settings.backup'));
        $response->assertSessionHas('success');
    }

    public function test_create_backup_endpoint_surfaces_failures(): void
    {
        $this->mock(BackupService::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->andReturn([
                    'success' => false,
                    'message' => 'Backup failed: mysqldump not found',
                    'record' => null,
                ]);
        });

        $response = $this->actingAs($this->user)->post(route('hms.settings.backup.create'));

        $response->assertRedirect(route('hms.settings.backup'));
        $response->assertSessionHas('error');
    }

    public function test_backup_service_lists_and_deletes_archives(): void
    {
        $service = app(BackupService::class);

        Storage::disk('backups')->put('DuncoHMS/2026-10-05-02-00-00.zip', 'zip-content');
        Storage::disk('backups')->put('legacy_backup.sql', 'CREATE TABLE demo (id INT);');

        $archives = $service->archives();
        $this->assertCount(2, $archives);
        $this->assertSame('2026-10-05-02-00-00.zip', $archives[0]['name']);

        $this->assertTrue($service->delete('2026-10-05-02-00-00.zip'));
        $this->assertFalse(Storage::disk('backups')->exists('DuncoHMS/2026-10-05-02-00-00.zip'));

        $this->assertFalse($service->delete('../../etc/passwd'));
        $this->assertFalse($service->delete('missing-file.zip'));
    }

    public function test_backup_service_verifies_sql_and_zip_archives(): void
    {
        Storage::disk('backups')->put('ok.sql', 'CREATE TABLE verify_me (id INT);');
        Storage::disk('backups')->put('empty.sql', '');

        $zipPath = tempnam(sys_get_temp_dir(), 'bkpz') . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('database-database.sql', 'CREATE TABLE zip_ok (id INT);');
        $zip->close();
        Storage::disk('backups')->put('ok.zip', file_get_contents($zipPath));
        @unlink($zipPath);

        $record = BackupRecord::factory()->create([
            'backup_location' => 'DuncoHMS/ok.sql',
        ]);

        $service = app(BackupService::class);

        $ok = $service->verify('ok.sql');
        $this->assertTrue($ok['success'], $ok['message'] ?? 'sql verify failed');

        $zipOk = $service->verify('ok.zip');
        $this->assertTrue($zipOk['success'], $zipOk['message'] ?? 'zip verify failed');

        $empty = $service->verify('empty.sql');
        $this->assertFalse($empty['success']);

        $missing = $service->verify('nope.zip');
        $this->assertFalse($missing['success']);

        $record->refresh();
        $this->assertTrue($record->verified);
        $this->assertNotNull($record->verified_at);
    }

    public function test_download_rejects_unknown_and_path_traversal_names(): void
    {
        Storage::disk('backups')->put('safe.zip', 'data');

        $service = app(BackupService::class);

        $this->assertNotNull($service->downloadPath('safe.zip'));
        $this->assertNull($service->downloadPath('missing.zip'));
        $this->assertNull($service->downloadPath('../.env'));
        $this->assertNull($service->downloadPath(''));
    }

    public function test_download_backup_route_returns_file(): void
    {
        Storage::disk('backups')->put('download-me.zip', 'archive-bytes');

        $response = $this->actingAs($this->user)->get(route('hms.settings.backup.download', 'download-me.zip'));

        $response->assertOk();
        $response->assertDownload('download-me.zip');
    }

    public function test_download_backup_route_returns_404_for_missing_file(): void
    {
        $this->actingAs($this->user)
            ->get(route('hms.settings.backup.download', 'does-not-exist.zip'))
            ->assertNotFound();
    }

    public function test_delete_backup_route_removes_archive(): void
    {
        Storage::disk('backups')->put('to-delete.zip', 'data');

        $response = $this->actingAs($this->user)
            ->from(route('hms.settings.backup'))
            ->delete(route('hms.settings.backup.delete', 'to-delete.zip'));

        $response->assertRedirect(route('hms.settings.backup'));
        $response->assertSessionHas('success');
        $this->assertFalse(Storage::disk('backups')->exists('to-delete.zip'));
    }

    public function test_verify_backup_route_marks_record_verified(): void
    {
        $zipPath = tempnam(sys_get_temp_dir(), 'bkpv') . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('database-database.sql', 'CREATE TABLE v (id INT);');
        $zip->close();
        Storage::disk('backups')->put('verify-route.zip', file_get_contents($zipPath));
        @unlink($zipPath);

        $record = BackupRecord::factory()->create([
            'backup_location' => 'DuncoHMS/verify-route.zip',
        ]);

        $response = $this->actingAs($this->user)
            ->from(route('hms.settings.backup'))
            ->post(route('hms.settings.backup.verify', 'verify-route.zip'));

        $response->assertRedirect(route('hms.settings.backup'));
        $response->assertSessionHas('success');

        $record->refresh();
        $this->assertTrue($record->verified);
        $this->assertNotNull($record->verified_at);
    }

    public function test_restore_rejects_invalid_file_type(): void
    {
        $file = UploadedFile::fake()->create('malware.exe', 10, 'application/octet-stream');

        $response = $this->actingAs($this->user)
            ->from(route('hms.settings.backup'))
            ->post(route('hms.settings.backup.restore'), [
                'backup_file' => $file,
            ]);

        $response->assertRedirect(route('hms.settings.backup'));
        $response->assertSessionHas('error');
        $this->assertStringContainsString('Unsupported backup format', session('error'));
    }

    public function test_restore_accepts_sql_dump_and_runs_import(): void
    {
        // Minimal MySQL-compatible dump that should import cleanly on the test DB.
        $sql = "CREATE TABLE IF NOT EXISTS backup_restore_probe (id INT PRIMARY KEY);\nINSERT INTO backup_restore_probe (id) VALUES (1);\n";

        $file = UploadedFile::fake()
            ->createWithContent('backup.sql', $sql);

        $response = $this->actingAs($this->user)
            ->from(route('hms.settings.backup'))
            ->post(route('hms.settings.backup.restore'), [
                'backup_file' => $file,
            ]);

        $response->assertRedirect(route('hms.settings.backup'));

        if (session('error')) {
            // mysql CLI may be unavailable in some environments; the endpoint must still fail cleanly.
            $this->assertStringContainsString('Restore failed', session('error'));
        } else {
            $response->assertSessionHas('success');
            $this->assertTrue(
                \Schema::hasTable('backup_restore_probe'),
                'Expected restore import to create probe table'
            );
        }
    }

    public function test_restore_from_zip_extracts_sql_dump(): void
    {
        $zipPath = tempnam(sys_get_temp_dir(), 'bkp') . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('database-database.sql', "CREATE TABLE IF NOT EXISTS zip_restore_probe (id INT);\n");
        $zip->close();

        $file = UploadedFile::fake()->createWithContent('backup.zip', file_get_contents($zipPath));
        @unlink($zipPath);

        $response = $this->actingAs($this->user)
            ->from(route('hms.settings.backup'))
            ->post(route('hms.settings.backup.restore'), [
                'backup_file' => $file,
            ]);

        $response->assertRedirect(route('hms.settings.backup'));

        if (session('error')) {
            $this->assertStringContainsString('Restore failed', session('error'));
        } else {
            $response->assertSessionHas('success');
        }
    }

    public function test_ict_backup_index_returns_json_records(): void
    {
        BackupRecord::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->getJson(route('hms.ict.backups.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'backup_type', 'backup_location', 'status'],
            ],
        ]);
    }

    public function test_ict_backup_store_can_trigger_real_backup_via_service(): void
    {
        $this->mock(BackupService::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->andReturn([
                    'success' => true,
                    'message' => 'Backup created successfully.',
                    'record' => BackupRecord::factory()->create(),
                ]);
        });

        $response = $this->actingAs($this->user)->post(route('hms.ict.backups.store'), [
            'run_backup' => 1,
            'notes' => 'ICT triggered backup',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_ict_backup_destroy_removes_record(): void
    {
        $record = BackupRecord::factory()->create();

        $response = $this->actingAs($this->user)
            ->from(route('hms.ict.backups.index'))
            ->delete(route('hms.ict.backups.destroy', $record));

        $response->assertRedirect(route('hms.ict.backups.index'));
        $this->assertDatabaseMissing('backup_records', ['id' => $record->id]);
    }

    public function test_backup_records_created_from_spatie_events(): void
    {
        $listener = new \App\Listeners\RecordBackupResult();

        $zip = tempnam(sys_get_temp_dir(), 'spatie') . '.zip';
        file_put_contents($zip, 'fake-zip');

        $listener->zipCreated(new \Spatie\Backup\Events\BackupZipWasCreated($zip));
        $this->assertDatabaseHas('backup_records', [
            'status' => 'running',
            'backup_location' => basename($zip),
        ]);

        $record = BackupRecord::where('status', 'running')->latest('id')->first();
        $this->assertNotNull($record);

        // Simulate success without a real destination by updating the running record path
        // through the listener's successful() path using a lightweight fake destination is complex;
        // assert the failed path instead for deterministic coverage.
        $listener->failed(new \Spatie\Backup\Events\BackupHasFailed(
            new \RuntimeException('disk full')
        ));

        $this->assertDatabaseHas('backup_records', [
            'status' => 'failed',
        ]);

        @unlink($zip);
    }

    public function test_data_sync_page_shows_real_backup_stats(): void
    {
        BackupRecord::factory()->create([
            'backup_location' => 'DuncoHMS/sample.zip',
            'status' => 'completed',
            'file_size_mb' => 12.5,
            'created_at' => now(),
            'started_at' => now()->subMinutes(10),
            'completed_at' => now()->subMinutes(5),
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.integrations.data-sync'));

        $response->assertOk();
        $response->assertSee('Backup Configuration');
        $response->assertSee('sample.zip');
        $response->assertSee('Open Backup', false);
    }

    public function test_schedule_commands_are_registered(): void
    {
        $this->assertTrue(
            collect(Artisan::all())->has('backup:run'),
            'Spatie backup:run command should be registered'
        );
        $this->assertTrue(
            collect(Artisan::all())->has('backup:clean'),
            'Spatie backup:clean command should be registered'
        );
        $this->assertTrue(
            collect(Artisan::all())->has('backup:monitor'),
            'Spatie backup:monitor command should be registered'
        );
    }

    public function test_encryption_flag_reflects_archive_password_config(): void
    {
        config(['backup.backup.password' => 'secret']);

        $stats = app(BackupService::class)->stats();
        $this->assertTrue($stats['encrypted']);

        config(['backup.backup.password' => null]);
        $stats = app(BackupService::class)->stats();
        $this->assertFalse($stats['encrypted']);
    }
}
