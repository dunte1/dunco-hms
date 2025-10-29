@extends('admin.layouts.app')

@section('title', 'Telemedicine Sessions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Telemedicine Sessions</h3>
                    <div class="card-tools">
                        <a href="{{ route('telemedicine.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Schedule Session
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Session ID</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Scheduled Time</th>
                                    <th>Type</th>
                                    <th>Platform</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                <tr>
                                    <td>{{ $session->session_id }}</td>
                                    <td>{{ $session->patient->first_name }} {{ $session->patient->last_name }}</td>
                                    <td>{{ $session->doctor->first_name }} {{ $session->doctor->last_name }}</td>
                                    <td>{{ $session->scheduled_time->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($session->session_type) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ ucfirst($session->platform) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $session->status === 'completed' ? 'success' : ($session->status === 'active' ? 'warning' : ($session->status === 'cancelled' ? 'danger' : 'info')) }}">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($session->status === 'scheduled')
                                        <button class="btn btn-sm btn-success" onclick="startSession({{ $session->id }})">
                                            <i class="fas fa-play"></i> Start
                                        </button>
                                        @elseif($session->status === 'active')
                                        <a href="{{ route('telemedicine.join', $session) }}" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-video"></i> Join
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="endSession({{ $session->id }})">
                                            <i class="fas fa-stop"></i> End
                                        </button>
                                        @endif
                                        <button class="btn btn-sm btn-info" onclick="viewSessionDetails({{ $session->id }})">
                                            <i class="fas fa-eye"></i> Details
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No telemedicine sessions scheduled</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $sessions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Session Details Modal -->
<div class="modal fade" id="sessionDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Session Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="sessionDetailsContent">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- End Session Modal -->
<div class="modal fade" id="endSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">End Session</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="endSessionForm">
                <div class="modal-body">
                    <input type="hidden" id="session_id" name="session_id">
                    <div class="form-group">
                        <label for="session_notes">Session Notes</label>
                        <textarea class="form-control" id="session_notes" name="notes" rows="4" placeholder="Enter session notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">End Session</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function startSession(sessionId) {
    if (confirm('Start this telemedicine session?')) {
        fetch(`/hms/telemedicine/${sessionId}/start`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Session started successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while starting session');
        });
    }
}

function endSession(sessionId) {
    $('#session_id').val(sessionId);
    $('#endSessionModal').modal('show');
}

function viewSessionDetails(sessionId) {
    $('#sessionDetailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#sessionDetailsModal').modal('show');
    
    fetch(`/hms/telemedicine/${sessionId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const session = data.data;
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Session Information</h6>
                            <p><strong>Session ID:</strong> ${session.session_id}</p>
                            <p><strong>Type:</strong> ${session.session_type}</p>
                            <p><strong>Platform:</strong> ${session.platform}</p>
                            <p><strong>Status:</strong> <span class="badge badge-info">${session.status}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Participants</h6>
                            <p><strong>Patient:</strong> ${session.patient.first_name} ${session.patient.last_name}</p>
                            <p><strong>Doctor:</strong> ${session.doctor.first_name} ${session.doctor.last_name}</p>
                            <p><strong>Scheduled:</strong> ${new Date(session.scheduled_time).toLocaleString()}</p>
                        </div>
                    </div>
                `;
                
                if (session.meeting_url) {
                    html += `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Meeting Information</h6>
                                <p><strong>Meeting URL:</strong> <a href="${session.meeting_url}" target="_blank">${session.meeting_url}</a></p>
                                <p><strong>Meeting ID:</strong> ${session.meeting_id}</p>
                            </div>
                        </div>
                    `;
                }
                
                if (session.notes) {
                    html += `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Notes</h6>
                                <p>${session.notes}</p>
                            </div>
                        </div>
                    `;
                }
                
                $('#sessionDetailsContent').html(html);
            } else {
                $('#sessionDetailsContent').html('<div class="alert alert-warning">Unable to load session details</div>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            $('#sessionDetailsContent').html('<div class="alert alert-danger">Error loading session details</div>');
        });
}

$('#endSessionForm').on('submit', function(e) {
    e.preventDefault();
    
    const sessionId = $('#session_id').val();
    const formData = new FormData(this);
    
    fetch(`/hms/telemedicine/${sessionId}/end`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Session ended successfully!');
            $('#endSessionModal').modal('hide');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while ending session');
    });
});
</script>
@endpush
