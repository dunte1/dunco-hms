@extends('admin.layouts.app')

@section('title', 'Batch Operations')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                <h2 class="text-white mb-0 fw-bold"><i class="fas fa-layer-group me-3"></i>Batch Operations</h2>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Batch Leave Requests -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="fas fa-calendar-times text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Batch Leave Management</h5>
                    <p class="text-muted mb-3">Approve or reject multiple leave requests at once</p>
                    <a href="{{ route('hms.hr.leave-requests.index') }}" class="btn btn-warning w-100">
                        <i class="fas fa-arrow-right me-2"></i>Manage Leave
                    </a>
                </div>
            </div>
        </div>

        <!-- Batch Attendance -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-clock text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Batch Attendance</h5>
                    <p class="text-muted mb-3">Mark attendance for multiple employees</p>
                    <a href="{{ route('hms.hr.attendance.index') }}" class="btn btn-success w-100">
                        <i class="fas fa-arrow-right me-2"></i>Mark Attendance
                    </a>
                </div>
            </div>
        </div>

        <!-- Batch ID Cards -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                        <i class="fas fa-id-card text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Batch ID Cards</h5>
                    <p class="text-muted mb-3">Generate ID cards for multiple patients or employees</p>
                    <button class="btn btn-pink w-100" data-bs-toggle="modal" data-bs-target="#idCardModal">
                        <i class="fas fa-arrow-right me-2"></i>Generate IDs
                    </button>
                </div>
            </div>
        </div>

        <!-- Batch Export -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="fas fa-download text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Batch Export</h5>
                    <p class="text-muted mb-3">Export data from any module in bulk</p>
                    <button class="btn btn-purple w-100" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="fas fa-arrow-right me-2"></i>Export Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Batch Payroll -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                        <i class="fas fa-money-bill-wave text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Batch Payroll</h5>
                    <p class="text-muted mb-3">Generate payroll for multiple employees</p>
                    <a href="{{ route('hms.hr.payrolls.index') }}" class="btn btn-danger w-100">
                        <i class="fas fa-arrow-right me-2"></i>Generate Payroll
                    </a>
                </div>
            </div>
        </div>

        <!-- Batch Notifications -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                        <i class="fas fa-bell text-white fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Batch Notifications</h5>
                    <p class="text-muted mb-3">Send notifications to multiple recipients</p>
                    <a href="{{ route('hms.messaging.bulk') }}" class="btn btn-info w-100">
                        <i class="fas fa-arrow-right me-2"></i>Send Messages
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ID Card Modal -->
<div class="modal fade" id="idCardModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-to-r from-pink-500 to-purple-600 text-white">
                <h5 class="modal-title"><i class="fas fa-id-card me-2"></i>Batch ID Card Generation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Select the type and IDs to generate ID cards for:</p>
                <form id="idCardForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Type</label>
                        <select name="type" class="form-select" required>
                            <option value="">Choose...</option>
                            <option value="patients">Patients</option>
                            <option value="employees">Employees</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Enter IDs (comma-separated)</label>
                        <textarea name="ids" class="form-control" rows="4" placeholder="Enter IDs separated by commas, e.g., 1, 2, 3, 4" required></textarea>
                        <small class="text-muted">Enter patient or employee IDs separated by commas</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitIdCardForm()">
                    <i class="fas fa-download me-2"></i>Generate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-to-r from-purple-500 to-indigo-600 text-white">
                <h5 class="modal-title"><i class="fas fa-download me-2"></i>Batch Data Export</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Export data from any module in various formats:</p>
                <form id="exportForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Module</label>
                        <select name="module" class="form-select" required>
                            <option value="">Choose...</option>
                            <option value="patients">Patients</option>
                            <option value="appointments">Appointments</option>
                            <option value="employees">Employees</option>
                            <option value="payroll">Payroll</option>
                            <option value="audit">Audit Logs</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date From</label>
                            <input type="date" name="date_from" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date To</label>
                            <input type="date" name="date_to" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Export Format</label>
                        <select name="format" class="form-select" required>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitExportForm()">
                    <i class="fas fa-download me-2"></i>Export
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function submitIdCardForm() {
    document.getElementById('idCardForm').action = '{{ route('hms.batch.id-cards') }}';
    document.getElementById('idCardForm').method = 'POST';
    document.getElementById('idCardForm').submit();
}

function submitExportForm() {
    document.getElementById('exportForm').action = '{{ route('hms.batch.export') }}';
    document.getElementById('exportForm').method = 'POST';
    document.getElementById('exportForm').submit();
}
</script>
@endsection

