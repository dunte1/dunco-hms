@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-palette text-primary mr-2"></i>
                Theme Customizer
            </h2>
            <p class="text-muted mb-0">Customize your hospital management system appearance</p>
        </div>
    </div>

    <form action="{{ route('hms.settings.theme.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <!-- Color Customization -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Color Scheme</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Primary Color</label>
                                    <input type="color" name="primary_color" class="form-control form-control-color" 
                                           value="{{ $currentTheme['primary_color'] ?? '#10b981' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Secondary Color</label>
                                    <input type="color" name="secondary_color" class="form-control form-control-color" 
                                           value="{{ $currentTheme['secondary_color'] ?? '#3b82f6' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Accent Color</label>
                                    <input type="color" name="accent_color" class="form-control form-control-color" 
                                           value="{{ $currentTheme['accent_color'] ?? '#f59e0b' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Typography -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Typography</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Font Family</label>
                            <select name="font_family" class="form-control">
                                <option value="Inter" {{ ($currentTheme['font_family'] ?? 'Inter') == 'Inter' ? 'selected' : '' }}>Inter</option>
                                <option value="Roboto" {{ ($currentTheme['font_family'] ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                <option value="Open Sans" {{ ($currentTheme['font_family'] ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                <option value="Poppins" {{ ($currentTheme['font_family'] ?? '') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Appearance -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Appearance</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="dark_mode" value="1" 
                                   id="darkMode" {{ ($currentTheme['dark_mode'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="darkMode">
                                Enable Dark Mode
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sidebar Style</label>
                            <select name="sidebar_style" class="form-control">
                                <option value="default" {{ ($currentTheme['sidebar_style'] ?? 'default') == 'default' ? 'selected' : '' }}>Default</option>
                                <option value="compact" {{ ($currentTheme['sidebar_style'] ?? '') == 'compact' ? 'selected' : '' }}>Compact</option>
                                <option value="wide" {{ ($currentTheme['sidebar_style'] ?? '') == 'wide' ? 'selected' : '' }}>Wide</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Logo -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Hospital Logo</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            @if(isset($currentTheme['logo']))
                                <small class="text-muted">Current logo: <img src="{{ $currentTheme['logo'] }}" alt="Logo" style="max-height: 50px;"></small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-save mr-2"></i> Save Theme
                        </button>
                        <a href="{{ route('hms.settings.theme.preview') }}" class="btn btn-info btn-block mb-2" target="_blank">
                            <i class="fas fa-eye mr-2"></i> Preview
                        </a>
                        <form action="{{ route('hms.settings.theme.export') }}" method="GET" class="mb-2">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-download mr-2"></i> Export Theme
                            </button>
                        </form>
                        <form action="{{ route('hms.settings.theme.reset') }}" method="POST" onsubmit="return confirm('Reset to default theme?');">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-block mb-2">
                                <i class="fas fa-undo mr-2"></i> Reset to Default
                            </button>
                        </form>
                        <label class="btn btn-secondary btn-block">
                            <i class="fas fa-upload mr-2"></i> Import Theme
                            <input type="file" id="importTheme" style="display: none;" accept=".json">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('importTheme').addEventListener('change', function() {
    const form = new FormData();
    form.append('theme_file', this.files[0]);
    
    fetch('{{ route("hms.settings.theme.import") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: form
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to import theme');
        }
    });
});
</script>
@endpush
@endsection

