@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-plus text-primary mr-2"></i>
                Create E-Prescription Template
            </h2>
        </div>
    </div>

    <form action="{{ route('hms.prescriptions.e-prescription.store-template') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Template Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Template Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control">
                                <option value="">General</option>
                                <option value="pediatric">Pediatric</option>
                                <option value="geriatric">Geriatric</option>
                                <option value="general">General</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Header Text</label>
                    <input type="text" name="header_text" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Footer Text</label>
                    <input type="text" name="footer_text" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Save Template
                </button>
                <a href="{{ route('hms.prescriptions.e-prescription.manage-templates') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

