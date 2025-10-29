@extends('admin.layouts.app')

@section('title', 'Google Maps Settings')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-map-marked-alt me-3"></i>Google Maps Settings
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.settings.index') }}" class="text-white-50">Settings</a></li>
                                <li class="breadcrumb-item text-white active">Maps</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success px-3 py-2 me-3">
                            <i class="fas fa-map me-1"></i>
                        </span>
                        Google Maps Configuration
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('hms.system.maps.update') }}">
                        @csrf

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Getting Started:</strong> To display Google Maps on your contact page, you need to:
                            <ol class="mb-0 mt-2">
                                <li>Get a Google Maps API key from <a href="https://console.cloud.google.com/google/maps-apis" target="_blank">Google Cloud Console</a></li>
                                <li>Enable the Maps JavaScript API for your project</li>
                                <li>Enter your API key below</li>
                                <li>Set your hospital location coordinates</li>
                            </ol>
                        </div>

                        <h5 class="fw-bold text-dark mb-4">API Configuration</h5>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Google Maps API Key</label>
                            <input type="text" name="google_maps_api_key" value="{{ old('google_maps_api_key', $settings['google_maps_api_key']) }}" class="form-control form-control-lg" placeholder="AIzaSyC...">
                            <small class="text-muted">Enter your Google Maps JavaScript API key. Keep this secure.</small>
                            @error('google_maps_api_key')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Location Settings</h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Latitude <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="map_latitude" value="{{ old('map_latitude', $settings['map_latitude']) }}" class="form-control form-control-lg" required>
                                <small class="text-muted">Hospital location latitude (e.g., -1.2921 for Nairobi)</small>
                                @error('map_latitude')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Longitude <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="map_longitude" value="{{ old('map_longitude', $settings['map_longitude']) }}" class="form-control form-control-lg" required>
                                <small class="text-muted">Hospital location longitude (e.g., 36.8219 for Nairobi)</small>
                                @error('map_longitude')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Quick Location Finder</label>
                            <div class="input-group">
                                <input type="text" id="location-search" class="form-control" placeholder="Search for your location (e.g., 'Nairobi, Kenya')">
                                <button type="button" class="btn btn-outline-secondary" onclick="searchLocation()">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                            </div>
                            <small class="text-muted">Search for your location and click to set coordinates automatically</small>
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Map Display Settings</h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Zoom Level <span class="text-danger">*</span></label>
                                <input type="range" name="map_zoom" value="{{ old('map_zoom', $settings['map_zoom']) }}" min="1" max="20" class="form-range" id="zoomSlider" oninput="updateZoomValue(this.value)" required>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">1 (World)</small>
                                    <strong id="zoomValue" class="text-primary">{{ old('map_zoom', $settings['map_zoom']) }}</strong>
                                    <small class="text-muted">20 (Street)</small>
                                </div>
                                @error('map_zoom')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Map Type <span class="text-danger">*</span></label>
                                <select name="map_type" class="form-select form-select-lg" required>
                                    <option value="roadmap" {{ $settings['map_type'] == 'roadmap' ? 'selected' : '' }}>Roadmap</option>
                                    <option value="satellite" {{ $settings['map_type'] == 'satellite' ? 'selected' : '' }}>Satellite</option>
                                    <option value="hybrid" {{ $settings['map_type'] == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    <option value="terrain" {{ $settings['map_type'] == 'terrain' ? 'selected' : '' }}>Terrain</option>
                                </select>
                                @error('map_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Map Height (px)</label>
                                <input type="number" name="map_height" value="{{ old('map_height', $settings['map_height']) }}" min="200" max="800" class="form-control form-control-lg">
                                <small class="text-muted">Height of the map on contact page (200-800px)</small>
                                @error('map_height')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Marker Color</label>
                                <input type="color" name="map_marker_color" value="{{ old('map_marker_color', $settings['map_marker_color']) }}" class="form-control form-control-lg" style="height: 48px;">
                                <small class="text-muted">Color of the location marker on the map</small>
                                @error('map_marker_color')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($settings['google_maps_api_key'] && $settings['map_latitude'] && $settings['map_longitude'])
                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Map Preview</h5>
                        <div id="map-preview" style="height: {{ $settings['map_height'] }}px; width: 100%; border: 2px solid #e5e7eb; border-radius: 8px;"></div>
                        @endif

                        <div class="d-flex justify-content-end gap-3 mt-5">
                            <a href="{{ route('hms.settings.index') }}" class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($settings['google_maps_api_key'] && $settings['map_latitude'] && $settings['map_longitude'])
<script>
function initMapPreview() {
    const location = {
        lat: {{ $settings['map_latitude'] }},
        lng: {{ $settings['map_longitude'] }}
    };
    
    const map = new google.maps.Map(document.getElementById('map-preview'), {
        zoom: {{ $settings['map_zoom'] }},
        center: location,
        mapTypeId: '{{ $settings['map_type'] }}'
    });
    
    new google.maps.Marker({
        position: location,
        map: map,
        title: '{{ SystemSetting::get('hospital_name', 'Hospital Location') }}',
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 10,
            fillColor: '{{ $settings['map_marker_color'] }}',
            fillOpacity: 1,
            strokeColor: '#ffffff',
            strokeWeight: 2
        }
    });
}

// Load Google Maps API for preview
(function() {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key={{ $settings['google_maps_api_key'] }}&callback=initMapPreview`;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
})();
</script>
@endif

<script>
function updateZoomValue(value) {
    document.getElementById('zoomValue').textContent = value;
}

function searchLocation() {
    const query = document.getElementById('location-search').value;
    if (!query) {
        alert('Please enter a location to search');
        return;
    }
    
    // Use Google Geocoding API (requires API key)
    const apiKey = '{{ $settings['google_maps_api_key'] }}';
    if (!apiKey) {
        alert('Please enter your Google Maps API key first');
        return;
    }
    
    fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(query)}&key=${apiKey}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'OK' && data.results.length > 0) {
                const location = data.results[0].geometry.location;
                document.querySelector('input[name="map_latitude"]').value = location.lat;
                document.querySelector('input[name="map_longitude"]').value = location.lng;
                alert(`Location found! Coordinates set to: ${location.lat}, ${location.lng}`);
            } else {
                alert('Location not found. Please try a different search term.');
            }
        })
        .catch(error => {
            alert('Error searching location. Please check your API key.');
        });
}
</script>

<style>
    .card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    }

    .form-range::-webkit-slider-thumb {
        background: #10b981;
    }

    .form-range::-moz-range-thumb {
        background: #10b981;
    }
</style>
@endsection

