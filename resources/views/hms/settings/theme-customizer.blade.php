@extends('admin.layouts.app')

@section('title', 'Theme & Branding')

@section('content')
<div class="container-fluid px-4">
    <div class="alert alert-info d-flex align-items-start">
        <i class="fas fa-info-circle me-2 mt-1"></i>
        <div>
            <strong>Theme settings have moved.</strong><br>
            Use the consolidated
            <a href="{{ route('hms.system.theme') }}" class="fw-bold">Theme &amp; Branding</a>
            page to update colors, logo, favicon, and dark mode.
        </div>
    </div>
</div>
@endsection
