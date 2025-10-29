@extends('admin.layouts.app')

@section('title', 'Create Hospital Branch')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-plus-circle me-3"></i>Create Hospital Branch
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.settings.branches') }}" class="text-white-50">Branches</a></li>
                                <li class="breadcrumb-item text-white active">Create</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Branch Form -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success px-3 py-2 me-3">
                            <i class="fas fa-plus me-1"></i>
                        </span>
                        Branch Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('hms.settings.branches.store') }}">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Branch Code <span class="text-danger">*</span></label>
                                <input type="text" name="branch_code" class="form-control form-control-lg" required placeholder="e.g., BR-001">
                                @error('branch_code')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Branch Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg" required placeholder="e.g., Main Branch">
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Branch description (optional)"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="3" required placeholder="Full address"></textarea>
                            @error('address')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control form-control-lg" required placeholder="Phone number">
                                @error('phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control form-control-lg" required placeholder="Email address">
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold text-dark mb-4">Manager Information (Optional)</h5>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Manager Name</label>
                                <input type="text" name="manager_name" class="form-control" placeholder="Manager name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Manager Phone</label>
                                <input type="tel" name="manager_phone" class="form-control" placeholder="Manager phone">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Manager Email</label>
                                <input type="email" name="manager_email" class="form-control" placeholder="Manager email">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_main_branch" id="is_main_branch" value="1">
                                <label class="form-check-label fw-bold text-dark" for="is_main_branch">
                                    Set as Main Branch
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Notes</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('hms.settings.branches') }}" class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                <i class="fas fa-save me-2"></i>Create Branch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    }
</style>
@endsection
