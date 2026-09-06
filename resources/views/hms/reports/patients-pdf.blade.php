<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Patient Report</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h1 { color: #10b981; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 12px; margin: 3px 0; }
        .header .subtitle { color: #999; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f3f4f6; padding: 8px 6px; text-align: left; font-weight: bold; border-bottom: 2px solid #10b981; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) { background: #f9fafb; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #e5e7eb; text-align: center; color: #999; font-size: 9px; }
        @media print {
            @page { size: A4; margin: 15mm; }
            body { padding: 0; margin: 0; }
            .no-print { display: none !important; }
            table { page-break-inside: avoid; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    @php
        $themeSettings = \App\Models\SystemSetting::getThemeSettings();
    @endphp
    <div class="header">
        @if(isset($themeSettings) && !empty($themeSettings['hospital_logo']))
            <img src="data:image/png;base64,{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
        @endif
        <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }} | Email: {{ \App\Models\SystemSetting::get('hospital_email', '') }}</p>
        <p class="subtitle">Patient Report | Generated: {{ now()->format('M d, Y h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Patient ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>DOB</th>
                <th>Gender</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
            <tr>
                <td>{{ $patient->patient_no }}</td>
                <td>{{ $patient->full_name }}</td>
                <td>{{ $patient->email ?? 'N/A' }}</td>
                <td>{{ $patient->phone ?? 'N/A' }}</td>
                <td>{{ $patient->dob ? date('M d, Y', strtotime($patient->dob)) : 'N/A' }}</td>
                <td>{{ ucfirst($patient->gender ?? 'N/A') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No patients found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>
