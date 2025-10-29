@extends('admin.layouts.app')

@section('title', 'RFID Tag Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">RFID Tag Management</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#createTagModal">
                            <i class="fas fa-plus"></i> Create Tag
                        </button>
                        <button class="btn btn-info" data-toggle="modal" data-target="#scanTagModal">
                            <i class="fas fa-qrcode"></i> Scan Tag
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tag ID</th>
                                    <th>Type</th>
                                    <th>Associated Name</th>
                                    <th>Status</th>
                                    <th>Last Seen</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tags as $tag)
                                <tr>
                                    <td>
                                        <code>{{ $tag->tag_id }}</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $tag->tag_type === 'patient' ? 'primary' : ($tag->tag_type === 'staff' ? 'success' : ($tag->tag_type === 'equipment' ? 'warning' : 'info')) }}">
                                            {{ ucfirst($tag->tag_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($tag->patient)
                                            {{ $tag->patient->first_name }} {{ $tag->patient->last_name }}
                                        @elseif($tag->employee)
                                            {{ $tag->employee->first_name }} {{ $tag->employee->last_name }}
                                        @else
                                            {{ $tag->associated_name ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $tag->status === 'active' ? 'success' : ($tag->status === 'inactive' ? 'secondary' : ($tag->status === 'lost' ? 'danger' : 'warning')) }}">
                                            {{ ucfirst($tag->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $tag->last_seen ? $tag->last_seen->format('M d, Y H:i') : 'Never' }}
                                    </td>
                                    <td>
                                        {{ $tag->last_location ?? 'Unknown' }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="scanTag('{{ $tag->tag_id }}')">
                                            <i class="fas fa-qrcode"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="updateTagStatus({{ $tag->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="viewTagHistory({{ $tag->id }})">
                                            <i class="fas fa-history"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No RFID tags found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $tags->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Tag Modal -->
<div class="modal fade" id="createTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create RFID Tag</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="createTagForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tag_id">Tag ID</label>
                        <input type="text" class="form-control" id="tag_id" name="tag_id" required>
                    </div>
                    <div class="form-group">
                        <label for="tag_type">Tag Type</label>
                        <select class="form-control" id="tag_type" name="tag_type" required>
                            <option value="">Select Type</option>
                            <option value="patient">Patient</option>
                            <option value="staff">Staff</option>
                            <option value="equipment">Equipment</option>
                            <option value="visitor">Visitor</option>
                        </select>
                    </div>
                    <div class="form-group" id="patient_group" style="display: none;">
                        <label for="patient_id">Patient</label>
                        <select class="form-control" id="patient_id" name="patient_id">
                            <option value="">Select Patient</option>
                            @foreach(\App\Models\Patient::latest()->get() as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="employee_group" style="display: none;">
                        <label for="employee_id">Employee</label>
                        <select class="form-control" id="employee_id" name="employee_id">
                            <option value="">Select Employee</option>
                            @foreach(\App\Models\Employee::latest()->get() as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="associated_name_group" style="display: none;">
                        <label for="associated_name">Associated Name</label>
                        <input type="text" class="form-control" id="associated_name" name="associated_name">
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Tag</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scan Tag Modal -->
<div class="modal fade" id="scanTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan RFID Tag</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="scanTagForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="scan_tag_id">Tag ID</label>
                        <input type="text" class="form-control" id="scan_tag_id" name="tag_id" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Scan Tag</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Tag Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="updateStatusForm">
                <div class="modal-body">
                    <input type="hidden" id="update_tag_id" name="tag_id">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="lost">Lost</option>
                            <option value="damaged">Damaged</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status_notes">Notes</label>
                        <textarea class="form-control" id="status_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#tag_type').on('change', function() {
    const type = $(this).val();
    
    // Hide all groups
    $('#patient_group, #employee_group, #associated_name_group').hide();
    $('#patient_id, #employee_id, #associated_name').prop('required', false);
    
    // Show relevant group
    if (type === 'patient') {
        $('#patient_group').show();
        $('#patient_id').prop('required', true);
    } else if (type === 'staff') {
        $('#employee_group').show();
        $('#employee_id').prop('required', true);
    } else {
        $('#associated_name_group').show();
        $('#associated_name').prop('required', true);
    }
});

$('#createTagForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("rfid.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('RFID tag created successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating tag');
    });
});

$('#scanTagForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("rfid.scan") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tag scanned successfully!');
            $('#scanTagModal').modal('hide');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while scanning tag');
    });
});

function scanTag(tagId) {
    $('#scan_tag_id').val(tagId);
    $('#scanTagModal').modal('show');
}

function updateTagStatus(tagId) {
    $('#update_tag_id').val(tagId);
    $('#updateStatusModal').modal('show');
}

function viewTagHistory(tagId) {
    fetch(`/hms/rfid/${tagId}/history`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = '<div class="table-responsive"><table class="table table-bordered">';
                html += '<thead><tr><th>Location</th><th>Timestamp</th><th>Status</th></tr></thead><tbody>';
                
                data.data.forEach(entry => {
                    html += `<tr>
                        <td>${entry.location}</td>
                        <td>${new Date(entry.timestamp).toLocaleString()}</td>
                        <td><span class="badge badge-info">${entry.status}</span></td>
                    </tr>`;
                });
                
                html += '</tbody></table></div>';
                
                // Show in modal or alert
                alert('Tag History:\n' + JSON.stringify(data.data, null, 2));
            } else {
                alert('Unable to load tag history');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading tag history');
        });
}

$('#updateStatusForm').on('submit', function(e) {
    e.preventDefault();
    
    const tagId = $('#update_tag_id').val();
    const formData = new FormData(this);
    
    fetch(`/hms/rfid/${tagId}/update-status`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tag status updated successfully!');
            $('#updateStatusModal').modal('hide');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating status');
    });
});
</script>
@endpush
