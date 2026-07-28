@extends('admin.layouts.app')

@section('title', 'HR Settings')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                <h2 class="text-white mb-0 fw-bold"><i class="fas fa-cog me-3"></i>HR Settings</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('hms.hr.settings.update') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <h5 class="fw-bold mb-3">Working Days & Hours</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Working Days</label>
                                <input type="text" name="working_days" class="form-control" value="{{ $settings['working_days'] }}" placeholder="Monday,Tuesday,Wednesday,Thursday,Friday">
                                <small class="text-muted">Comma-separated list</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Working Hours Start</label>
                                <input type="time" name="working_hours_start" class="form-control" value="{{ $settings['working_hours_start'] }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Working Hours End</label>
                                <input type="time" name="working_hours_end" class="form-control" value="{{ $settings['working_hours_end'] }}">
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold mb-3">Salary Configuration</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Salary Cycle <span class="text-danger">*</span></label>
                                <select name="salary_cycle" class="form-select" required>
                                    <option value="monthly" {{ $settings['salary_cycle'] == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="biweekly" {{ $settings['salary_cycle'] == 'biweekly' ? 'selected' : '' }}>Biweekly</option>
                                    <option value="weekly" {{ $settings['salary_cycle'] == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Salary Day <span class="text-danger">*</span></label>
                                <input type="number" name="salary_day" class="form-control" value="{{ $settings['salary_day'] }}" min="1" max="31" required>
                                <small class="text-muted">Day of month (1-31)</small>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold mb-3">Leave Policy</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Default Leave Days <span class="text-danger">*</span></label>
                                <input type="number" name="default_leave_days" class="form-control" value="{{ $settings['default_leave_days'] }}" min="0" required>
                                <small class="text-muted">Default annual leave days per employee</small>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold mb-3">System Configuration</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Auto ID Prefix <span class="text-danger">*</span></label>
                                <input type="text" name="auto_id_prefix" class="form-control" value="{{ $settings['auto_id_prefix'] }}" maxlength="10" required>
                                <small class="text-muted">Prefix for auto-generated employee IDs (e.g., EMP)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Document Expiry Reminder Days <span class="text-danger">*</span></label>
                                <input type="number" name="document_expiry_reminder_days" class="form-control" value="{{ $settings['document_expiry_reminder_days'] }}" min="1" required>
                                <small class="text-muted">Days before document expiry to send reminder</small>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Save Settings
                                </button>
                                <a href="{{ route('hms.hr.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

