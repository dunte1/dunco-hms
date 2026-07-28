@extends('admin.layouts.app')

@section('title', 'Create Announcement')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <h2 class="text-white mb-0 fw-bold"><i class="fas fa-plus-circle me-3"></i>Create Announcement</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('hms.hr.announcements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Content <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="6" required>{{ old('content') }}</textarea>
                                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Target Audience <span class="text-danger">*</span></label>
                                <select name="target_audience" id="target_audience" class="form-select @error('target_audience') is-invalid @enderror" required onchange="toggleTargetFields()">
                                    <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>All Employees</option>
                                    <option value="department" {{ old('target_audience') == 'department' ? 'selected' : '' }}>Specific Department</option>
                                    <option value="designation" {{ old('target_audience') == 'designation' ? 'selected' : '' }}>Specific Designation</option>
                                    <option value="specific" {{ old('target_audience') == 'specific' ? 'selected' : '' }}>Specific Employees</option>
                                </select>
                                @error('target_audience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6" id="department_field" style="display: none;">
                                <label class="form-label fw-bold">Department</label>
                                <select name="department_id" class="form-select">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="designation_field" style="display: none;">
                                <label class="form-label fw-bold">Designation</label>
                                <select name="designation_id" class="form-select">
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="employees_field" style="display: none;">
                                <label class="form-label fw-bold">Select Employees</label>
                                <select name="target_employee_ids[]" class="form-select" multiple size="5">
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ in_array($employee->id, old('target_employee_ids', [])) ? 'selected' : '' }}>{{ $employee->full_name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">End Date</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Attachment</label>
                                <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Max 5MB. Formats: PDF, DOC, DOCX, JPG, PNG</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Announcement
                                </button>
                                <a href="{{ route('hms.hr.announcements.index') }}" class="btn btn-secondary">
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

<script>
function toggleTargetFields() {
    const target = document.getElementById('target_audience').value;
    document.getElementById('department_field').style.display = target === 'department' ? 'block' : 'none';
    document.getElementById('designation_field').style.display = target === 'designation' ? 'block' : 'none';
    document.getElementById('employees_field').style.display = target === 'specific' ? 'block' : 'none';
}
toggleTargetFields();
</script>
@endsection

