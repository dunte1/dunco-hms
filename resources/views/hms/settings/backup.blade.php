@extends('admin.layouts.app')

@section('title', 'Backup & Restore')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-database me-3"></i>Backup & Restore
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.settings.index') }}" class="text-white-50">Settings</a></li>
                                <li class="breadcrumb-item text-white active">Backup</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup Actions -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-cyan-subtle text-cyan px-3 py-2 me-3">
                            <i class="fas fa-download me-1"></i>
                        </span>
                        Create Backup
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">Create a compressed backup of your database and uploaded files. Archives are stored securely and can be restored later.</p>
                    <form method="POST" action="{{ route('hms.settings.backup.create') }}" id="createBackupForm">
                        @csrf
                        <button type="submit" id="createBackupBtn" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none;">
                            <i class="fas fa-database me-2"></i>Create Backup Now
                        </button>
                    </form>
                    @if(!empty($stats['encrypted']))
                        <div class="mt-3 text-success small">
                            <i class="fas fa-lock me-1"></i> Archive encryption is enabled.
                        </div>
                    @else
                        <div class="mt-3 text-warning small">
                            <i class="fas fa-exclamation-triangle me-1"></i> Archive encryption is disabled. Set <code>BACKUP_ARCHIVE_PASSWORD</code> in .env for production.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success px-3 py-2 me-3">
                            <i class="fas fa-upload me-1"></i>
                        </span>
                        Restore Backup
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">Upload a previous backup archive (.zip) or database dump (.sql) to restore your system. A safety snapshot is taken automatically before restore.</p>
                    <form method="POST" action="{{ route('hms.settings.backup.restore') }}" enctype="multipart/form-data" id="restoreForm">
                        @csrf
                        <input type="file" name="backup_file" id="backup_file" accept=".sql,.txt,.zip" required class="d-none" onchange="updateFileName(this)">
                        <button type="button" onclick="document.getElementById('backup_file').click()" class="btn btn-success btn-lg w-100" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                            <i class="fas fa-file-upload me-2"></i>Upload Backup File
                        </button>
                        <div id="fileInfo" class="mt-3 text-sm text-muted" style="display: none;"></div>
                        <button type="submit" id="restoreBtn" class="btn btn-warning btn-lg w-100 mt-2" style="display: none;">
                            <i class="fas fa-redo me-2"></i>Restore from File
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Archives</div>
                    <div class="fs-4 fw-bold text-dark">{{ $stats['archive_count'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Total Size</div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($stats['total_size_mb'] ?? 0, 2) }} MB</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Last Backup</div>
                    <div class="fs-6 fw-bold text-dark">{{ $lastBackup ?? 'Not yet created' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Schedule</div>
                    <div class="fs-6 fw-bold text-dark">
                        @if(!empty($stats['schedule_enabled']))
                            Daily at {{ $stats['schedule'] ?? '02:00' }}
                        @else
                            Disabled
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing Backups -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark">Existing Backups</h5>
                </div>
                <div class="card-body p-4">
                    @if(isset($backups) && count($backups) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Backup Name</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Created At</th>
                                        <th>Encrypted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>{{ $backup['name'] }}</td>
                                            <td><span class="badge bg-secondary text-uppercase">{{ $backup['type'] }}</span></td>
                                            <td>{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                                            <td>{{ $backup['created_at'] }}</td>
                                            <td>
                                                @if(!empty($backup['encrypted']))
                                                    <span class="badge bg-success"><i class="fas fa-lock me-1"></i>Yes</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('hms.settings.backup.download', $backup['name']) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download me-1"></i>Download
                                                </a>
                                                <form method="POST" action="{{ route('hms.settings.backup.verify', $backup['name']) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-check me-1"></i>Verify
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('hms.settings.backup.delete', $backup['name']) }}" class="d-inline" onsubmit="return confirm('Delete this backup permanently?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash me-1"></i>Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted">No backups found yet. Create your first backup above.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Backup History -->
    @if(!empty($records) && $records->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-4">
                        <h5 class="mb-0 fw-bold text-dark">Backup History</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Size (MB)</th>
                                        <th>Started</th>
                                        <th>Completed</th>
                                        <th>Verified</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($records as $record)
                                        <tr>
                                            <td class="text-break">{{ $record->backup_location }}</td>
                                            <td>{{ $record->backup_type }}</td>
                                            <td>
                                                @if($record->status === 'completed')
                                                    <span class="badge bg-success">completed</span>
                                                @elseif($record->status === 'failed')
                                                    <span class="badge bg-danger">failed</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">{{ $record->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $record->file_size_mb ?? '—' }}</td>
                                            <td>{{ optional($record->started_at)->format('Y-m-d H:i:s') }}</td>
                                            <td>{{ optional($record->completed_at)->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                @if($record->verified)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td class="text-break small">{{ \Illuminate\Support\Str::limit($record->notes, 60) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Backup Information -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-cyan-subtle text-cyan px-3 py-2 me-3">
                            <i class="fas fa-info-circle me-1"></i>
                        </span>
                        Backup Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info mb-4">
                        <h6 class="fw-bold mb-2"><i class="fas fa-lightbulb me-2"></i>Important Notes:</h6>
                        <ul class="mb-0">
                            <li>Automated backups run daily via Spatie laravel-backup (time: {{ $stats['schedule'] ?? '02:00' }}).</li>
                            <li>Backups include the database and uploaded files under <code>storage/app</code>.</li>
                            <li>Retention policy keeps recent backups and prunes older archives automatically.</li>
                            <li>Store copies of backups off-server. Local-only backups do not protect against host loss.</li>
                            <li>Test restoration periodically using the Restore panel above.</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-clock me-2 text-primary"></i>Last Backup</h6>
                                <p class="text-muted mb-0">{{ $lastBackup ?? 'Not yet created' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-hdd me-2 text-success"></i>Backup Location</h6>
                                <p class="text-muted mb-0">{{ $stats['path'] ?? storage_path('app/backups') }}</p>
                                <p class="text-muted small mb-0 mt-1">Disk: <code>{{ $stats['disk'] ?? 'backups' }}</code> · Retention: {{ $stats['retention_days'] ?? 7 }} days (all backups)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
    }

    * {
        transition: all 0.3s ease;
    }
</style>

<script>
    document.getElementById('createBackupForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('createBackupBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Backup...';
    });

    function updateFileName(input) {
        const fileInfo = document.getElementById('fileInfo');
        const restoreBtn = document.getElementById('restoreBtn');

        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            const fileSize = (input.files[0].size / 1024).toFixed(2);
            fileInfo.innerHTML = `<i class="fas fa-file me-1"></i>Selected: ${fileName} (${fileSize} KB)`;
            fileInfo.style.display = 'block';
            restoreBtn.style.display = 'block';
        } else {
            fileInfo.style.display = 'none';
            restoreBtn.style.display = 'none';
        }
    }

    document.getElementById('restoreForm').addEventListener('submit', function(e) {
        if (!confirm('WARNING: This will replace your current database with the backup. Are you absolutely sure you want to continue?')) {
            e.preventDefault();
            return false;
        }

        const btn = document.getElementById('restoreBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Restoring...';
    });
</script>
@endsection
