<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BI Dashboard - {{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; line-height: 1.45; }
        .header { text-align: center; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 3px solid #2563eb; }
        .header h1 { color: #2563eb; font-size: 18px; margin: 6px 0 2px; }
        .header p { color: #666; font-size: 11px; margin: 2px 0; }
        .header .title { font-size: 15px; font-weight: bold; color: #111; margin-top: 8px; text-transform: uppercase; letter-spacing: 0.04em; }
        .meta { color: #666; font-size: 10px; margin-top: 4px; }
        .kpis { display: table; width: 100%; margin: 14px 0 18px; border-spacing: 6px 0; }
        .kpi { display: table-cell; width: 25%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 8px; text-align: center; vertical-align: top; }
        .kpi .label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.03em; }
        .kpi .value { font-size: 16px; font-weight: bold; color: #1e293b; margin-top: 4px; }
        h2 { color: #2563eb; font-size: 13px; margin: 16px 0 8px; border-bottom: 2px solid #bfdbfe; padding-bottom: 4px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #e2e8f0; padding: 6px 8px; text-align: left; font-size: 10px; }
        th { background-color: #eff6ff; font-weight: bold; color: #1e40af; }
        tr:nth-child(even) td { background: #f8fafc; }
        .footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
        @page { size: A4 landscape; margin: 12mm; }
        @media print { body { padding: 0; margin: 0; } }
    </style>
</head>
<body>
    @php
        $logoHtml = '';
        $logoFile = public_path('hospital-logo.png');
        if (file_exists($logoFile)) {
            $logoHtml = '<img src="'.htmlspecialchars($logoFile).'" style="height: 46px; margin-bottom: 6px;">';
        }
    @endphp
    <div class="header">
        @if($logoHtml){!! $logoHtml !!}@endif
        <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }} | {{ \App\Models\SystemSetting::get('hospital_email', '') }}</p>
        <div class="title">Business Intelligence Dashboard</div>
        <div class="meta">Generated {{ now()->format('F d, Y \a\t H:i') }} by {{ auth()->user()->name ?? 'System' }}</div>
    </div>

    <div class="kpis">
        <div class="kpi"><div class="label">Total Patients</div><div class="value">{{ number_format($metrics['total_patients'] ?? 0) }}</div></div>
        <div class="kpi"><div class="label">Appointments</div><div class="value">{{ number_format($metrics['total_appointments'] ?? 0) }}</div></div>
        <div class="kpi"><div class="label">Total Revenue</div><div class="value">${{ number_format((float)($metrics['total_revenue'] ?? 0), 2) }}</div></div>
        <div class="kpi"><div class="label">Bed Occupancy</div><div class="value">{{ number_format((float)($metrics['bed_occupancy'] ?? 0), 1) }}%</div></div>
    </div>

    <h2>Additional Metrics</h2>
    <table>
        <tr>
            <th>Patient Satisfaction</th>
            <th>Avg Wait Time</th>
            <th>Revenue Growth</th>
            <th>Patient Growth</th>
        </tr>
        <tr>
            <td>{{ number_format((float)($metrics['patient_satisfaction'] ?? 0), 1) }}%</td>
            <td>{{ (int)($metrics['average_wait_time'] ?? 0) }} min</td>
            <td>{{ number_format((float)($metrics['revenue_growth'] ?? 0), 1) }}%</td>
            <td>{{ number_format((float)($metrics['patient_growth'] ?? 0), 1) }}%</td>
        </tr>
    </table>

    <h2>Recent Analytics Reports</h2>
    @if($analytics && $analytics->count())
        <table>
            <tr>
                <th>#</th>
                <th>Metric</th>
                <th>Type</th>
                <th>Date Range</th>
                <th>Granularity</th>
                <th>Created</th>
            </tr>
            @foreach($analytics as $i => $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->metric_name ?? '—' }}</td>
                    <td>{{ $a->metric_type ?? '—' }}</td>
                    <td>{{ $a->date_from?->format('M d, Y') ?? '—' }} → {{ $a->date_to?->format('M d, Y') ?? '—' }}</td>
                    <td>{{ ucfirst($a->granularity ?? 'daily') }}</td>
                    <td>{{ $a->created_at?->format('M d, Y H:i') ?? '—' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p style="font-size:11px;color:#64748b;">No analytics reports generated yet.</p>
    @endif

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | Confidential Business Intelligence Report</p>
    </div>
</body>
</html>
