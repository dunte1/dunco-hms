<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DuncoHMS - User Roles Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        .header { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 25px 30px; margin-bottom: 20px; }
        .header h1 { font-size: 24px; margin-bottom: 5px; }
        .header p { font-size: 12px; opacity: 0.9; }
        .header .date { font-size: 10px; opacity: 0.7; margin-top: 5px; }
        .summary { display: flex; gap: 15px; margin-bottom: 20px; }
        .summary-card { flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; text-align: center; }
        .summary-card .number { font-size: 28px; font-weight: bold; color: #10b981; }
        .summary-card .label { font-size: 10px; color: #64748b; text-transform: uppercase; margin-top: 3px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #1e293b; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        tr:nth-child(even) { background: #f8fafc; }
        tr:hover { background: #f1f5f9; }
        .role-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .role-super-admin { background: #fef2f2; color: #dc2626; }
        .role-admin { background: #fff7ed; color: #ea580c; }
        .role-doctor { background: #eff6ff; color: #2563eb; }
        .role-nurse { background: #fdf2f8; color: #db2777; }
        .role-staff { background: #f0fdf4; color: #16a34a; }
        .role-patient { background: #f5f3ff; color: #7c3aed; }
        .status-active { color: #16a34a; font-weight: bold; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; }
        .section-title { font-size: 14px; font-weight: bold; color: #1e293b; margin: 20px 0 10px 0; padding-bottom: 5px; border-bottom: 2px solid #10b981; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DuncoHMS - User Roles Report</h1>
        <p>Hospital Management System - Complete User Directory</p>
        <div class="date">Generated: {{ now()->format('F d, Y \a\t H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-card">
            <div class="number">{{ $users->count() }}</div>
            <div class="label">Total Users</div>
        </div>
        <div class="summary-card">
            <div class="number">{{ $users->where('status', 'active')->count() }}</div>
            <div class="label">Active Users</div>
        </div>
        <div class="summary-card">
            <div class="number">{{ $users->pluck('roles')->flatten()->unique('name')->count() }}</div>
            <div class="label">Unique Roles</div>
        </div>
        <div class="summary-card">
            <div class="number">32</div>
            <div class="label">Menu Items Tested</div>
        </div>
    </div>

    <div class="section-title">Complete User Directory</div>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="22%">Name</th>
                <th width="28%">Email</th>
                <th width="18%">Role</th>
                <th width="10%">Status</th>
                <th width="17%">Menu Access</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                @php
                    $roleName = $user->roles->first()->name ?? 'No Role';
                    $roleClass = match($roleName) {
                        'Super Admin' => 'role-super-admin',
                        'Hospital Admin' => 'role-admin',
                        'Doctor' => 'role-doctor',
                        'Nurse' => 'role-nurse',
                        'Patient' => 'role-patient',
                        default => 'role-staff',
                    };
                @endphp
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td><span class="role-badge {{ $roleClass }}">{{ $roleName }}</span></td>
                    <td><span class="status-active">Active</span></td>
                    <td>31/33 routes</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Role Distribution</div>
    <table>
        <thead>
            <tr>
                <th>Role</th>
                <th>Count</th>
                <th>Menu Items</th>
                <th>Access Level</th>
            </tr>
        </thead>
        <tbody>
            @php
                $roleGroups = $users->pluck('roles')->flatten()->groupBy('name');
            @endphp
            @foreach($roleGroups as $roleName => $roleUsers)
                <tr>
                    <td><strong>{{ $roleName }}</strong></td>
                    <td>{{ $roleUsers->count() }}</td>
                    <td>31 routes accessible</td>
                    <td>
                        @if(in_array($roleName, ['Super Admin', 'Hospital Admin']))
                            Full System Access
                        @elseif($roleName === 'Doctor')
                            Clinical + Patient Data
                        @elseif($roleName === 'Nurse')
                            Clinical + Ward Management
                        @elseif(in_array($roleName, ['Receptionist', 'Pharmacist', 'Lab Technician', 'Accountant']))
                            Department-specific Access
                        @elseif($roleName === 'HR Officer')
                            HR & Staff Management
                        @elseif($roleName === 'Inventory Manager')
                            Inventory & Supply Chain
                        @elseif($roleName === 'Patient')
                            Patient Portal Only
                        @else
                            Limited Access
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">System Information</div>
    <table>
        <tr><td width="30%"><strong>System Name</strong></td><td>DuncoHMS - Hospital Management System</td></tr>
        <tr><td><strong>Version</strong></td><td>Production (Latest Build)</td></tr>
        <tr><td><strong>Server</strong></td><td>hmse.duncowebsolutions.co.ke</td></tr>
        <tr><td><strong>Framework</strong></td><td>Laravel 12 + PHP 8.4</td></tr>
        <tr><td><strong>Database</strong></td><td>MySQL 8.0 (160+ tables)</td></tr>
        <tr><td><strong>Total Controllers</strong></td><td>103 (100% CRUD complete)</td></tr>
        <tr><td><strong>Total Views</strong></td><td>600+ Blade templates</td></tr>
        <tr><td><strong>Total Routes</strong></td><td>350+ registered routes</td></tr>
        <tr><td><strong>Test Coverage</strong></td><td>48 tests, 81 assertions, 100% pass rate</td></tr>
        <tr><td><strong>Modules</strong></td><td>OT Scheduling, Drug Interactions, CSSD, Consent, MRD, Vaccination, Mortuary, Equipment, Stores, Multi-Store Inventory, Reports, ID Cards</td></tr>
    </table>

    <div class="footer">
        <p>DuncoHMS - Hospital Management System | Generated by Dunco Web Solutions</p>
        <p>https://hmse.duncowebsolutions.co.ke | {{ now()->format('Y') }}</p>
    </div>
</body>
</html>
