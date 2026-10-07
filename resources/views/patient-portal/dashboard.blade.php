<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Portal Dashboard - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; margin: 0; }
        .portal-sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
        }
        .portal-sidebar .nav-link {
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            margin: 2px 0;
            min-height: 44px;
        }
        .portal-sidebar .nav-link:hover,
        .portal-sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .main-content { background: #f8f9fa; min-height: 100vh; }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #667eea;
            height: 100%;
        }
        .stat-card .icon { font-size: 1.75rem; color: #667eea; }
        .portal-topbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .portal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 1040;
        }
        .portal-overlay.show { display: block; }
        @media (max-width: 767.98px) {
            .portal-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 280px;
                max-width: 85vw;
                transform: translateX(-105%);
                transition: transform 0.25s ease-in-out;
                z-index: 1050;
                overflow-y: auto;
            }
            .portal-sidebar.open { transform: translateX(0); }
        }
    </style>
</head>
<body>
    <div class="portal-overlay" id="portalOverlay" onclick="closePortalNav()"></div>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 portal-sidebar p-3" id="portalSidebar">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        Patient Portal
                    </h5>
                    <button type="button" class="btn btn-sm btn-link text-white d-md-none" onclick="closePortalNav()" aria-label="Close menu">
                        <i class="fas fa-times fa-lg"></i>
                    </button>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="{{ route('patient-portal.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="{{ route('patient-portal.appointments') }}">
                        <i class="fas fa-calendar me-2"></i>Appointments
                    </a>
                    <a class="nav-link" href="{{ route('patient-portal.prescriptions') }}">
                        <i class="fas fa-prescription me-2"></i>Prescriptions
                    </a>
                    <a class="nav-link" href="{{ route('patient-portal.lab-results') }}">
                        <i class="fas fa-flask me-2"></i>Lab Results
                    </a>
                    <a class="nav-link" href="{{ route('patient-portal.medical-history') }}">
                        <i class="fas fa-file-medical me-2"></i>Medical History
                    </a>
                    <a class="nav-link" href="{{ route('patient-portal.billing') }}">
                        <i class="fas fa-credit-card me-2"></i>Billing
                    </a>
                    <a class="nav-link" href="{{ route('patient-portal.profile') }}">
                        <i class="fas fa-user me-2"></i>Profile
                    </a>
                    <form method="POST" action="{{ route('patient-portal.logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100 text-white border-0">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </nav>

                @php
                    $emergencyPhone = \App\Models\SystemSetting::get('emergency_phone_1', '+254 700 000 000');
                    $ambulancePhone = \App\Models\SystemSetting::get('ambulance_phone', '+254 700 000 002');
                    $emergencyClean = preg_replace('/[^0-9+]/', '', $emergencyPhone);
                    $ambulanceClean = preg_replace('/[^0-9+]/', '', $ambulancePhone);
                @endphp
                <div class="mt-4 p-3 bg-danger bg-opacity-10 rounded">
                    <h6 class="text-danger mb-2"><i class="fas fa-phone-alt me-1"></i> Emergency</h6>
                    <a href="tel:{{ $emergencyClean }}" class="d-block text-danger text-decoration-none mb-1">
                        <i class="fas fa-phone me-1"></i> Emergency: {{ $emergencyPhone }}
                    </a>
                    <a href="tel:{{ $ambulanceClean }}" class="d-block text-warning text-decoration-none">
                        <i class="fas fa-ambulance me-1"></i> Ambulance: {{ $ambulancePhone }}
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="portal-topbar">
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-outline-primary me-2 d-md-none" onclick="openPortalNav()" aria-label="Open menu">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h5 class="mb-0">Dashboard</h5>
                    </div>
                    <div class="text-muted small text-end">
                        Welcome back,
                        <strong>{{ $patient->first_name ?? ($account->username ?? 'Patient') }} {{ $patient->last_name ?? '' }}</strong>
                    </div>
                </div>

                <div class="p-3 p-md-4">
                    <!-- Stats Cards -->
                    <div class="row mb-4 g-3">
                        <div class="col-6 col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small">Appointments</h6>
                                        <h3 class="mb-0">{{ $stats['total_appointments'] ?? 0 }}</h3>
                                    </div>
                                    <div class="icon"><i class="fas fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small">Upcoming</h6>
                                        <h3 class="mb-0">{{ $stats['upcoming_appointments'] ?? 0 }}</h3>
                                    </div>
                                    <div class="icon"><i class="fas fa-calendar-check"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small">Prescriptions</h6>
                                        <h3 class="mb-0">{{ $stats['total_prescriptions'] ?? 0 }}</h3>
                                    </div>
                                    <div class="icon"><i class="fas fa-prescription"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small">Lab Results</h6>
                                        <h3 class="mb-0">{{ $stats['pending_lab_results'] ?? 0 }}</h3>
                                    </div>
                                    <div class="icon"><i class="fas fa-flask"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom py-3">
                                    <h6 class="mb-0">Recent Appointments</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Doctor</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse(($recentAppointments ?? collect()) as $appointment)
                                                    <tr>
                                                        <td>{{ optional($appointment->scheduled_at ?? $appointment->appointment_date)->format('M d, Y') }}</td>
                                                        <td>{{ $appointment->doctor->full_name ?? $appointment->doctor->name ?? '—' }}</td>
                                                        <td><span class="badge bg-secondary">{{ $appointment->status ?? '—' }}</span></td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="3" class="text-center text-muted py-4">No appointments yet</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom py-3">
                                    <h6 class="mb-0">Recent Prescriptions</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Doctor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse(($recentPrescriptions ?? collect()) as $prescription)
                                                    <tr>
                                                        <td>{{ optional($prescription->created_at)->format('M d, Y') }}</td>
                                                        <td>{{ $prescription->doctor->full_name ?? $prescription->doctor->name ?? '—' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="2" class="text-center text-muted py-4">No prescriptions yet</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPortalNav() {
            document.getElementById('portalSidebar').classList.add('open');
            document.getElementById('portalOverlay').classList.add('show');
        }
        function closePortalNav() {
            document.getElementById('portalSidebar').classList.remove('open');
            document.getElementById('portalOverlay').classList.remove('show');
        }
    </script>
</body>
</html>
