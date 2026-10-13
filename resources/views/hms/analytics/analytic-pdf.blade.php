<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $analytic->metric_name ?? 'Analytics' }} - {{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; line-height: 1.45; }
        .header { text-align: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 3px solid #7c3aed; }
        .header h1 { color: #7c3aed; font-size: 17px; margin: 6px 0 2px; }
        .header p { color: #666; font-size: 11px; margin: 2px 0; }
        .header .title { font-size: 14px; font-weight: bold; color: #111; margin-top: 8px; }
        .meta { color: #666; font-size: 10px; margin-top: 4px; }
        .info { display: table; width: 100%; margin-bottom: 14px; border-spacing: 4px 0; }
        .info .cell { display: table-cell; width: 25%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 5px; padding: 8px; }
        .info .label { font-size: 9px; color: #64748b; text-transform: uppercase; }
        .info .value { font-size: 12px; font-weight: bold; color: #1e293b; margin-top: 2px; }
        h2 { color: #7c3aed; font-size: 12px; margin: 14px 0 6px; border-bottom: 2px solid #ddd6fe; padding-bottom: 3px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        th, td { border: 1px solid #e2e8f0; padding: 5px 7px; text-align: left; font-size: 10px; }
        th { background-color: #f5f3ff; font-weight: bold; color: #5b21b6; }
        tr:nth-child(even) td { background: #fafafa; }
        .insights { background: #fffbeb; border: 1px solid #fde68a; border-radius: 5px; padding: 10px; font-size: 11px; margin-top: 8px; }
        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
        @page { size: A4; margin: 14mm; }
        @media print { body { padding: 0; margin: 0; } }
    </style>
</head>
<body>
    @php
        $logoHtml = '';
        $logoFile = public_path('hospital-logo.png');
        if (file_exists($logoFile)) {
            $logoHtml = '<img src="'.htmlspecialchars($logoFile).'" style="height: 42px; margin-bottom: 6px;">';
        }
    @endphp
    <div class="header">
        @if($logoHtml){!! $logoHtml !!}@endif
        <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <div class="title">{{ $analytic->metric_name ?? 'Analytics Report' }}</div>
        <div class="meta">Report #{{ $analytic->id }} · Generated {{ now()->format('F d, Y \a\t H:i') }}</div>
    </div>

    <div class="info">
        <div class="cell"><div class="label">Metric Type</div><div class="value">{{ $analytic->metric_type ?? '—' }}</div></div>
        <div class="cell"><div class="label">Date From</div><div class="value">{{ $analytic->date_from?->format('M d, Y') ?? '—' }}</div></div>
        <div class="cell"><div class="label">Date To</div><div class="value">{{ $analytic->date_to?->format('M d, Y') ?? '—' }}</div></div>
        <div class="cell"><div class="label">Granularity</div><div class="value">{{ ucfirst($analytic->granularity ?? 'daily') }}</div></div>
    </div>

    <h2>Data Points</h2>
    @if(!empty($dataPoints))
        <table>
            <tr>
                <th>#</th>
                <th>Period</th>
                <th>Value</th>
                <th>Type</th>
            </tr>
            @foreach($dataPoints as $i => $point)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $point['date'] ?? '—' }}</td>
                    <td>{{ is_numeric($point['value'] ?? null) ? number_format((float) $point['value'], 2) : ($point['value'] ?? '—') }}</td>
                    <td>{{ $point['type'] ?? '—' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p style="font-size:11px;color:#64748b;">No data points for this report.</p>
    @endif

    @if(!empty($predictions))
        <h2>Predictions</h2>
        <table>
            <tr>
                <th>Period</th>
                <th>Predicted Value</th>
                <th>Confidence</th>
            </tr>
            @foreach($predictions as $p)
                <tr>
                    <td>{{ $p['period'] ?? '—' }}</td>
                    <td>{{ is_numeric($p['predicted_value'] ?? null) ? number_format((float) $p['predicted_value'], 2) : ($p['predicted_value'] ?? '—') }}</td>
                    <td>{{ $p['confidence'] ?? '—' }}%</td>
                </tr>
            @endforeach
        </table>
    @endif

    <h2>Insights</h2>
    <div class="insights">{{ $insights ?: ($analytic->insights ?? 'No insights available.') }}</div>

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | Analytics Report</p>
    </div>
</body>
</html>
