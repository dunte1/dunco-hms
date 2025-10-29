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
                    <p class="text-muted mb-4">Create a backup of your database and files. This will save all your data for recovery purposes.</p>
                    <form method="POST" action="{{ route('hms.settings.backup.create') }}" id="createBackupForm">
                        @csrf
                        <button type="submit" id="createBackupBtn" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none;">
                            <i class="fas fa-database me-2"></i>Create Backup Now
                        </button>
                    </form>
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
                    <p class="text-muted mb-4">Upload a previously created backup file to restore your system to a previous state.</p>
                    <form method="POST" action="{{ route('hms.settings.backup.restore') }}" enctype="multipart/form-data" id="restoreForm">
                        @csrf
                        <input type="file" name="backup_file" id="backup_file" accept=".sql,.txt" required class="d-none" onchange="updateFileName(this)">
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

    <!-- Existing Backups -->
    @if(isset($backups) && count($backups) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-4">
                        <h5 class="mb-0 fw-bold text-dark">Existing Backups</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Backup Name</th>
                                        <th>Size</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>{{ $backup['name'] }}</td>
                                            <td>{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                                            <td>{{ $backup['created_at'] }}</td>
                                            <td>
                                                <a href="{{ route('hms.settings.backup.download', $backup['name']) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download me-1"></i>Download
                                                </a>
                                            </td>
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
                            <li>It is recommended to create backups regularly, preferably daily.</li>
                            <li>Store backups in a secure location separate from the main server.</li>
                            <li>Test backup restoration periodically to ensure backups are valid.</li>
                            <li>Backups include database data and system files.</li>
                            <li>Large backups may take several minutes to complete.</li>
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
                                <p class="text-muted mb-0">/storage/backups/</p>
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
    // Create Backup Form Handler
    document.getElementById('createBackupForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('createBackupBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Backup...';
    });
    
    // File Upload Handler
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
    
    // Confirm before restoring
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
