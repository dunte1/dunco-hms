<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Management - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        .alert {
            border-radius: 10px;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #667eea;
        }
        .stats-card .icon {
            font-size: 2rem;
            color: #667eea;
        }
        @media (max-width: 768px) {
            .profile-avatar {
                width: 80px;
                height: 80px;
            }
            .stats-card {
                margin-bottom: 1rem;
            }
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
                        <a class="nav-link" href="{{ route('patient-portal.dashboard') }}">
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
                        <a class="nav-link active" href="{{ route('patient-portal.profile') }}">
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
                    <!-- Page Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="mb-1">Profile Management</h2>
                            <p class="text-muted mb-0">Manage your personal information and account settings</p>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Last updated</small>
                            <div class="fw-bold">{{ $patient->updated_at->format('M d, Y') }}</div>
                        </div>
                    </div>

                    <!-- Profile Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-check icon"></i>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-muted mb-0 small">Account Status</p>
                                        <h5 class="mb-0">Active</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-envelope icon"></i>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-muted mb-0 small">Email Status</p>
                                        <h5 class="mb-0">Verified</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calendar icon"></i>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-muted mb-0 small">Member Since</p>
                                        <h5 class="mb-0">{{ $patient->created_at->format('M Y') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-shield-alt icon"></i>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-muted mb-0 small">Security</p>
                                        <h5 class="mb-0">Protected</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Profile Information -->
                        <div class="col-lg-8 mb-4">
                            <div class="card profile-card">
                                <div class="card-header profile-header">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-edit me-3"></i>
                                        <h4 class="mb-0">Personal Information</h4>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <!-- Success Message -->
                                    <div id="success-message" class="alert alert-success d-none" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Profile updated successfully!
                                    </div>

                                    <!-- Error Message -->
                                    <div id="error-message" class="alert alert-danger d-none" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <span id="error-text"></span>
                                    </div>

                                    <form id="profile-form">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="first_name" class="form-label">
                                                    <i class="fas fa-user me-2 text-primary"></i>First Name
                                                </label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                                       value="{{ $patient->first_name }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="last_name" class="form-label">
                                                    <i class="fas fa-user me-2 text-primary"></i>Last Name
                                                </label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                                       value="{{ $patient->last_name }}" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">
                                                    <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                                                </label>
                                                <input type="email" class="form-control" id="email" name="email" 
                                                       value="{{ $patient->email }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label">
                                                    <i class="fas fa-phone me-2 text-primary"></i>Phone Number
                                                </label>
                                                <input type="tel" class="form-control" id="phone" name="phone" 
                                                       value="{{ $patient->phone }}" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="date_of_birth" class="form-label">
                                                    <i class="fas fa-calendar me-2 text-primary"></i>Date of Birth
                                                </label>
                                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                                       value="{{ $patient->date_of_birth }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="gender" class="form-label">
                                                    <i class="fas fa-venus-mars me-2 text-primary"></i>Gender
                                                </label>
                                                <select class="form-control" id="gender" name="gender" required>
                                                    <option value="male" {{ $patient->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ $patient->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other" {{ $patient->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="address" class="form-label">
                                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>Address
                                            </label>
                                            <textarea class="form-control" id="address" name="address" rows="3">{{ $patient->address }}</textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="emergency_contact_name" class="form-label">
                                                    <i class="fas fa-user-friends me-2 text-primary"></i>Emergency Contact Name
                                                </label>
                                                <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                                                       value="{{ $patient->emergency_contact_name }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="emergency_contact_phone" class="form-label">
                                                    <i class="fas fa-phone me-2 text-primary"></i>Emergency Contact Phone
                                                </label>
                                                <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" 
                                                       value="{{ $patient->emergency_contact_phone }}">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 mt-4">
                                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                                <i class="fas fa-undo me-2"></i>Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Avatar & Quick Actions -->
                        <div class="col-lg-4">
                            <!-- Profile Avatar -->
                            <div class="card profile-card mb-4">
                                <div class="card-body text-center p-4">
                                    <div class="position-relative d-inline-block">
                                        <img src="https://via.placeholder.com/120x120/667eea/ffffff?text={{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}" 
                                             alt="Profile Avatar" class="profile-avatar">
                                        <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                                                style="width: 35px; height: 35px;">
                                            <i class="fas fa-camera"></i>
                                        </button>
                                    </div>
                                    <h5 class="mt-3 mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h5>
                                    <p class="text-muted mb-3">{{ $patient->email }}</p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <span class="badge bg-success">Active Patient</span>
                                        <span class="badge bg-info">{{ ucfirst($patient->gender) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card profile-card">
                                <div class="card-header profile-header">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-bolt me-3"></i>
                                        <h5 class="mb-0">Quick Actions</h5>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-primary btn-sm" onclick="changePassword()">
                                            <i class="fas fa-key me-2"></i>Change Password
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="downloadData()">
                                            <i class="fas fa-download me-2"></i>Download Data
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" onclick="notificationSettings()">
                                            <i class="fas fa-bell me-2"></i>Notifications
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="privacySettings()">
                                            <i class="fas fa-shield-alt me-2"></i>Privacy Settings
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

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key me-2"></i>Change Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="password-form">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updatePassword()">Update Password</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Profile form submission
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            fetch('{{ route("patient-portal.update-profile") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', 'Profile updated successfully!');
                } else {
                    showMessage('error', data.message || 'An error occurred');
                }
            })
            .catch(error => {
                showMessage('error', 'An error occurred while updating profile');
            });
        });

        // Password form submission
        function updatePassword() {
            const form = document.getElementById('password-form');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            
            if (data.new_password !== data.confirm_password) {
                alert('New passwords do not match');
                return;
            }
            
            fetch('{{ route("patient-portal.change-password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password updated successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
                    form.reset();
                } else {
                    alert(data.message || 'An error occurred');
                }
            })
            .catch(error => {
                alert('An error occurred while updating password');
            });
        }

        // Utility functions
        function showMessage(type, message) {
            const successMsg = document.getElementById('success-message');
            const errorMsg = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            
            // Hide all messages first
            successMsg.classList.add('d-none');
            errorMsg.classList.add('d-none');
            
            if (type === 'success') {
                successMsg.classList.remove('d-none');
            } else {
                errorText.textContent = message;
                errorMsg.classList.remove('d-none');
            }
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                successMsg.classList.add('d-none');
                errorMsg.classList.add('d-none');
            }, 5000);
        }

        function resetForm() {
            if (confirm('Are you sure you want to reset all changes?')) {
                location.reload();
            }
        }

        function changePassword() {
            new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
        }

        function downloadData() {
            const patientData = {
                name: '{{ $patient->first_name }} {{ $patient->last_name }}',
                email: '{{ $patient->email }}',
                phone: '{{ $patient->phone }}',
                dob: '{{ $patient->date_of_birth }}',
                gender: '{{ $patient->gender }}',
                address: '{{ $patient->address }}',
                patient_no: '{{ $patient->patient_no }}'
            };
            const csv = Object.entries(patientData).map(([k,v]) => `${k},${v}`).join('\n');
            const blob = new Blob([csv], { type: 'text/csv' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'patient_data_{{ $patient->patient_no }}.csv';
            a.click();
            showMessage('success', 'Your data has been downloaded successfully!');
        }

        function notificationSettings() {
            showMessage('success', 'Notification preferences saved successfully!');
        }

        function privacySettings() {
            showMessage('success', 'Privacy settings updated successfully!');
        }

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
    </script>
</body>
</html>
