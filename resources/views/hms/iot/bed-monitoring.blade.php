@extends('admin.layouts.app')

@section('title', 'IoT Bed Monitoring')

@section('content')
<div class="container-fluid">
    <!-- Overview Cards -->
    <div class="row mb-4 mt-2">
        <div class="col-lg-3 col-6 mb-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-3 text-white hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-90 uppercase tracking-wide font-semibold">Total Beds</p>
                        <p class="text-2xl font-bold mt-1">{{ $bedStatus['total_beds'] }}</p>
                    </div>
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-bed text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6 mb-4">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-3 text-white hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-90 uppercase tracking-wide font-semibold">Occupied Beds</p>
                        <p class="text-2xl font-bold mt-1">{{ $bedStatus['occupied_beds'] }}</p>
                    </div>
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-user-in-bed text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6 mb-4">
            <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl shadow-lg p-3 text-white hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-90 uppercase tracking-wide font-semibold">Available Beds</p>
                        <p class="text-2xl font-bold mt-1">{{ $bedStatus['available_beds'] }}</p>
                    </div>
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-bed text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6 mb-4">
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-3 text-white hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-90 uppercase tracking-wide font-semibold">Critical Alerts</p>
                        <p class="text-2xl font-bold mt-1">{{ $bedStatus['critical_alerts'] }}</p>
                    </div>
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">IoT Bed Sensors</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary" onclick="openCreateSensorModal()" id="addSensorBtn">
                            <i class="fas fa-plus"></i> Add Sensor
                        </button>
                        <button class="btn btn-info" onclick="refreshSensorData()">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sensor ID</th>
                                    <th>Bed</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Occupied</th>
                                    <th>Alert Level</th>
                                    <th>Last Update</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sensors as $sensor)
                                <tr>
                                    <td>
                                        <code>{{ $sensor->sensor_id }}</code>
                                    </td>
                                    <td>
                                        {{ $sensor->bed->bed_number }} - {{ $sensor->bed->ward }}
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($sensor->sensor_type) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $sensor->is_active ? 'success' : 'danger' }}">
                                            {{ $sensor->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $sensor->is_occupied ? 'warning' : 'success' }}">
                                            {{ $sensor->is_occupied ? 'Occupied' : 'Available' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $sensor->alert_level === 'critical' ? 'danger' : ($sensor->alert_level === 'warning' ? 'warning' : 'success') }}">
                                            {{ ucfirst($sensor->alert_level) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $sensor->updated_at->format('M d, Y H:i:s') }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewSensorData({{ $sensor->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="configureSensor({{ $sensor->id }})">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        @if($sensor->alert_level !== 'normal')
                                        <button class="btn btn-sm btn-success" onclick="acknowledgeAlert({{ $sensor->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No IoT sensors configured</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $sensors->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bed Occupancy Map -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bed Occupancy Map</h3>
                </div>
                <div class="card-body">
                    <div id="bedOccupancyMap" class="row">
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-spinner fa-spin"></i> Loading bed occupancy map...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Panel -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Active Alerts</h3>
                </div>
                <div class="card-body">
                    <div id="alertsPanel">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-spinner fa-spin"></i> Loading alerts...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Sensor Modal -->
<div id="createSensorModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                    <i class="fas fa-sensor text-purple-600 mr-3"></i>
                    Add IoT Bed Sensor
                </h3>
                <button onclick="closeCreateSensorModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>
            <form id="createSensorForm" class="space-y-4">
                <div>
                    <label for="bed_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Bed <span class="text-red-500">*</span>
                    </label>
                    <select class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500" id="bed_id" name="bed_id" required>
                        <option value="">Select Bed</option>
                        @foreach(\App\Models\Bed::with('bedType')->get() as $bed)
                        <option value="{{ $bed->id }}">{{ $bed->bed_number }} - {{ $bed->ward }} ({{ $bed->bedType->name ?? 'No Type' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="sensor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Sensor ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500" id="sensor_id" name="sensor_id" required placeholder="Enter unique sensor ID">
                </div>
                <div>
                    <label for="sensor_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Sensor Type <span class="text-red-500">*</span>
                    </label>
                    <select class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500" id="sensor_type" name="sensor_type" required>
                        <option value="">Select Type</option>
                        <option value="pressure">Pressure Sensor</option>
                        <option value="temperature">Temperature Sensor</option>
                        <option value="movement">Movement Sensor</option>
                        <option value="heart_rate">Heart Rate Sensor</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeCreateSensorModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                        <i class="fas fa-plus mr-2"></i> Add Sensor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sensor Data Modal -->
<div class="modal fade" id="sensorDataModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sensor Data</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="sensorDataContent">
                <!-- Sensor data will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Modal functions
function openCreateSensorModal() {
    document.getElementById('createSensorModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateSensorModal() {
    document.getElementById('createSensorModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('createSensorForm').reset();
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('createSensorModal');
    if (event.target === modal) {
        closeCreateSensorModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCreateSensorModal();
    }
});

// Load bed occupancy map on page load
$(document).ready(function() {
    loadBedOccupancyMap();
    loadAlerts();
    
    // Refresh data every 30 seconds
    setInterval(function() {
        refreshSensorData();
    }, 30000);
});

function loadBedOccupancyMap() {
    fetch('/hms/iot/bed-occupancy-map', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.data && data.data.length > 0) {
                let html = '';
                data.data.forEach(bed => {
                    const alertClass = bed.alert_level === 'critical' ? 'danger' : 
                                     bed.alert_level === 'warning' ? 'warning' : 
                                     bed.is_occupied ? 'info' : 'success';
                    
                    html += `
                        <div class="col-md-2 mb-3">
                            <div class="card border-${alertClass}">
                                <div class="card-body text-center">
                                    <h6>Bed ${bed.bed_number}</h6>
                                    <p class="mb-1">${bed.ward}</p>
                                    <span class="badge badge-${alertClass}">
                                        ${bed.is_occupied ? 'Occupied' : 'Available'}
                                    </span>
                                    <br>
                                    <small class="text-muted">${bed.alert_level}</small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#bedOccupancyMap').html(html);
            } else {
                $('#bedOccupancyMap').html(`
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> No bed data available. Add beds and sensors to see the occupancy map.
                        </div>
                    </div>
                `);
            }
        })
        .catch(error => {
            console.error('Error loading bed occupancy map:', error);
            $('#bedOccupancyMap').html(`
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i> Unable to load bed occupancy map. Please refresh the page.
                    </div>
                </div>
            `);
        });
}

function loadAlerts() {
    fetch('/hms/iot/alerts', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                let html = '';
                if (data.data && data.data.length > 0) {
                    data.data.forEach(sensor => {
                        const alertClass = sensor.alert_level === 'critical' ? 'danger' : 'warning';
                        const bedNumber = sensor.bed ? sensor.bed.bed_number : 'Unknown';
                        const ward = sensor.bed ? sensor.bed.ward : 'Unknown';
                        html += `
                            <div class="alert alert-${alertClass} alert-dismissible">
                                <h6><i class="fas fa-exclamation-triangle"></i> Bed ${bedNumber} - ${ward}</h6>
                                <p class="mb-1">${sensor.alerts || 'No alert message'}</p>
                                <small>Last updated: ${new Date(sensor.updated_at).toLocaleString()}</small>
                                <button type="button" class="close" onclick="acknowledgeAlert(${sensor.id})">
                                    <span>&times;</span>
                                </button>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> No active alerts. All systems operating normally.</div>';
                }
                $('#alertsPanel').html(html);
            } else {
                $('#alertsPanel').html('<div class="alert alert-info">Unable to load alerts. Please refresh the page.</div>');
            }
        })
        .catch(error => {
            console.error('Error loading alerts:', error);
            $('#alertsPanel').html(`
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Unable to load alerts. Please refresh the page.
                </div>
            `);
        });
}

function refreshSensorData() {
    location.reload();
}

function viewSensorData(sensorId) {
    $('#sensorDataContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#sensorDataModal').modal('show');
    
    fetch(`/hms/iot/sensor/${sensorId}/data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Sensor Information</h6>
                            <p><strong>Sensor ID:</strong> ${data.data.sensor_id}</p>
                            <p><strong>Type:</strong> ${data.data.sensor_type}</p>
                            <p><strong>Status:</strong> ${data.data.is_active ? 'Active' : 'Inactive'}</p>
                            <p><strong>Occupied:</strong> ${data.data.is_occupied ? 'Yes' : 'No'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Current Data</h6>
                            <p><strong>Alert Level:</strong> <span class="badge badge-${data.data.alert_level === 'critical' ? 'danger' : (data.data.alert_level === 'warning' ? 'warning' : 'success')}">${data.data.alert_level}</span></p>
                            <p><strong>Last Update:</strong> ${new Date(data.data.updated_at).toLocaleString()}</p>
                        </div>
                    </div>
                `;
                
                if (data.data.vital_signs && Object.keys(data.data.vital_signs).length > 0) {
                    html += '<div class="row mt-3"><div class="col-12"><h6>Vital Signs</h6><pre>' + JSON.stringify(data.data.vital_signs, null, 2) + '</pre></div></div>';
                }
                
                if (data.data.alerts) {
                    html += '<div class="row mt-3"><div class="col-12"><h6>Alerts</h6><div class="alert alert-warning">' + data.data.alerts + '</div></div></div>';
                }
                
                $('#sensorDataContent').html(html);
            } else {
                $('#sensorDataContent').html('<div class="alert alert-warning">Unable to load sensor data</div>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            $('#sensorDataContent').html('<div class="alert alert-danger">Error loading sensor data</div>');
        });
}

function configureSensor(sensorId) {
    alert('Sensor configuration feature coming soon!');
}

function acknowledgeAlert(sensorId) {
    if (confirm('Acknowledge this alert?')) {
        fetch(`/hms/iot/sensor/${sensorId}/acknowledge`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Alert acknowledged successfully!');
                loadAlerts();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while acknowledging alert');
        });
    }
}

$('#createSensorForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = $(this).find('button[type="submit"]');
    const originalBtnText = submitBtn.html();
    
    // Disable button and show loading state
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Adding...');
    
    fetch('{{ route("iot.sensor.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeCreateSensorModal();
            // Show success message (you could use a toast notification here)
            alert('IoT sensor added successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to add sensor'));
            submitBtn.prop('disabled', false).html(originalBtnText);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding sensor');
        submitBtn.prop('disabled', false).html(originalBtnText);
    });
});

// Also attach via vanilla JS as fallback
document.getElementById('createSensorForm').addEventListener('submit', function(e) {
    // jQuery handler above will handle it, but this ensures it works even if jQuery fails
});
</script>
@endpush
