@extends('admin.layouts.app')

@section('title', 'Edit Leave Type')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-edit me-3"></i>Edit Leave Type
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.leave-types.index') }}" class="text-white-50">Leave Types</a></li>
                                <li class="breadcrumb-item text-white active">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('hms.hr.leave-types.update', $leaveType) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Leave Type Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $leaveType->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Color</label>
                                <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $leaveType->color) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Default Days Per Year <span class="text-danger">*</span></label>
                                <input type="number" name="default_days" class="form-control @error('default_days') is-invalid @enderror" value="{{ old('default_days', $leaveType->default_days) }}" min="0" required>
                                @error('default_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $leaveType->description) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="carry_forward" value="1" id="carry_forward" {{ old('carry_forward', $leaveType->carry_forward) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="carry_forward">Allow Carry Forward</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="requires_approval" value="1" id="requires_approval" {{ old('requires_approval', $leaveType->requires_approval) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requires_approval">Requires Approval</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $leaveType->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Update Leave Type
                                </button>
                                <a href="{{ route('hms.hr.leave-types.index') }}" class="btn btn-secondary">
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

