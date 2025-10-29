<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal Dashboard - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #667eea;
        }
        .stat-card .icon {
            font-size: 2rem;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="p-3">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-user-md me-2"></i>
                        Patient Portal
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="#dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="#appointments">
                            <i class="fas fa-calendar me-2"></i>Appointments
                        </a>
                        <a class="nav-link" href="#prescriptions">
                            <i class="fas fa-prescription me-2"></i>Prescriptions
                        </a>
                        <a class="nav-link" href="#lab-results">
                            <i class="fas fa-flask me-2"></i>Lab Results
                        </a>
                        <a class="nav-link" href="#medical-history">
                            <i class="fas fa-file-medical me-2"></i>Medical History
                        </a>
                        <a class="nav-link" href="#billing">
                            <i class="fas fa-credit-card me-2"></i>Billing
                        </a>
                        <a class="nav-link" href="#profile">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                        <a class="nav-link" href="#" onclick="logout()">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Dashboard</h2>
                        <div class="text-muted">
                            Welcome back, <strong>John Doe</strong>
                        </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Appointments</h6>
                                        <h3 class="mb-0">{{ $stats['total_appointments'] }}</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Upcoming</h6>
                                        <h3 class="mb-0">{{ $stats['upcoming_appointments'] }}</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Prescriptions</h6>
                                        <h3 class="mb-0">{{ $stats['total_prescriptions'] }}</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-prescription"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Lab Results</h6>
                                        <h3 class="mb-0">{{ $stats['pending_lab_results'] }}</h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-flask"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Appointments -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recent Appointments</h5>
                                </div>
                                <div class="card-body">
                                    @forelse($recentAppointments as $appointment)
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                        <div>
                                            <h6 class="mb-1">{{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</h6>
                                            <small class="text-muted">{{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time }}</small>
                                        </div>
                                        <span class="badge badge-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    @empty
                                    <p class="text-muted text-center">No recent appointments</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Prescriptions -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recent Prescriptions</h5>
                                </div>
                                <div class="card-body">
                                    @forelse($recentPrescriptions as $prescription)
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                        <div>
                                            <h6 class="mb-1">{{ $prescription->doctor->first_name }} {{ $prescription->doctor->last_name }}</h6>
                                            <small class="text-muted">{{ $prescription->created_at->format('M d, Y') }}</small>
                                        </div>
                                        <span class="badge badge-info">{{ $prescription->items->count() }} items</span>
                                    </div>
                                    @empty
                                    <p class="text-muted text-center">No recent prescriptions</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <button class="btn btn-primary w-100" onclick="bookAppointment()">
                                                <i class="fas fa-calendar-plus me-2"></i>Book Appointment
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <button class="btn btn-info w-100" onclick="viewPrescriptions()">
                                                <i class="fas fa-prescription me-2"></i>View Prescriptions
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <button class="btn btn-success w-100" onclick="viewLabResults()">
                                                <i class="fas fa-flask me-2"></i>Lab Results
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <button class="btn btn-warning w-100" onclick="updateProfile()">
                                                <i class="fas fa-user-edit me-2"></i>Update Profile
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                fetch('/patient-portal/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '/patient-portal/login';
                    }
                });
            }
        }
        
        function bookAppointment() {
            // Implementation for booking appointment
            alert('Book appointment feature coming soon!');
        }
        
        function viewPrescriptions() {
            // Implementation for viewing prescriptions
            alert('View prescriptions feature coming soon!');
        }
        
        function viewLabResults() {
            // Implementation for viewing lab results
            alert('View lab results feature coming soon!');
        }
        
        function updateProfile() {
            // Implementation for updating profile
            alert('Update profile feature coming soon!');
        }
    </script>
</body>
</html>
