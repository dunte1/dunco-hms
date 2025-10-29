@extends('admin.layouts.app')

@section('title', 'Lab Equipment Integration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lab Equipment Integration (LIS)</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addEquipmentModal">
                            <i class="fas fa-plus"></i> Add Equipment
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Equipment Name</th>
                                    <th>Type</th>
                                    <th>Model</th>
                                    <th>Serial Number</th>
                                    <th>Status</th>
                                    <th>Connection</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($equipment as $item)
                                <tr>
                                    <td>{{ $item->equipment_name }}</td>
                                    <td>{{ ucfirst($item->equipment_type) }}</td>
                                    <td>{{ $item->model_number }}</td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>
                                        <span class="badge badge-{{ $item->status === 'active' ? 'success' : ($item->status === 'maintenance' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->is_connected ? 'success' : 'danger' }}">
                                            {{ $item->is_connected ? 'Connected' : 'Disconnected' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="testConnection({{ $item->id }})">
                                            <i class="fas fa-plug"></i> Test
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="viewResults({{ $item->id }})">
                                            <i class="fas fa-chart-line"></i> Results
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="configureEquipment({{ $item->id }})">
                                            <i class="fas fa-cog"></i> Configure
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No lab equipment configured</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $equipment->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Equipment Modal -->
<div class="modal fade" id="addEquipmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Lab Equipment</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addEquipmentForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="equipment_name">Equipment Name</label>
                                <input type="text" class="form-control" id="equipment_name" name="equipment_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="equipment_type">Equipment Type</label>
                                <select class="form-control" id="equipment_type" name="equipment_type" required>
                                    <option value="">Select Type</option>
                                    <option value="analyzer">Analyzer</option>
                                    <option value="centrifuge">Centrifuge</option>
                                    <option value="microscope">Microscope</option>
                                    <option value="incubator">Incubator</option>
                                    <option value="spectrophotometer">Spectrophotometer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="model_number">Model Number</label>
                                <input type="text" class="form-control" id="model_number" name="model_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serial_number">Serial Number</label>
                                <input type="text" class="form-control" id="serial_number" name="serial_number" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="manufacturer">Manufacturer</label>
                                <input type="text" class="form-control" id="manufacturer" name="manufacturer" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="connection_type">Connection Type</label>
                                <select class="form-control" id="connection_type" name="connection_type" required>
                                    <option value="">Select Connection</option>
                                    <option value="tcp">TCP/IP</option>
                                    <option value="http">HTTP</option>
                                    <option value="serial">Serial</option>
                                    <option value="usb">USB</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ip_address">IP Address</label>
                                <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="192.168.1.100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="port">Port</label>
                                <input type="number" class="form-control" id="port" name="port" placeholder="8080">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Equipment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Results Modal -->
<div class="modal fade" id="resultsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Equipment Results</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resultsContent">
                <!-- Results will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function testConnection(equipmentId) {
    if (confirm('Test connection to this equipment?')) {
        fetch(`/hms/integration/lab-equipment/${equipmentId}/test`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.connected ? 'Equipment connected successfully!' : 'Failed to connect to equipment');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while testing connection');
        });
    }
}

function viewResults(equipmentId) {
    $('#resultsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading results...</div>');
    $('#resultsModal').modal('show');
    
    fetch(`/hms/integration/lab-equipment/${equipmentId}/results`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = '<div class="table-responsive"><table class="table table-bordered">';
                html += '<thead><tr><th>Date</th><th>Test</th><th>Result</th><th>Status</th></tr></thead><tbody>';
                
                data.data.data.forEach(result => {
                    html += `<tr>
                        <td>${new Date(result.created_at).toLocaleDateString()}</td>
                        <td>Lab Test</td>
                        <td>${JSON.stringify(result.processed_data)}</td>
                        <td><span class="badge badge-success">${result.result_status}</span></td>
                    </tr>`;
                });
                
                html += '</tbody></table></div>';
                $('#resultsContent').html(html);
            } else {
                $('#resultsContent').html('<div class="alert alert-warning">No results available</div>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            $('#resultsContent').html('<div class="alert alert-danger">Error loading results</div>');
        });
}

function configureEquipment(equipmentId) {
    alert('Equipment configuration feature coming soon!');
}

$('#addEquipmentForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("integration.lab-equipment.create") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Equipment added successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding equipment');
    });
});
</script>
@endpush
