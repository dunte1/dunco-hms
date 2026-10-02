<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Disease Surveillance Report</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #ef4444; }
        .header h1 { color: #ef4444; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 12px; margin: 3px 0; }
        .header .subtitle { color: #999; font-size: 10px; }
        .summary { display: table; width: 100%; margin-bottom: 20px; }
        .summary-box { display: table-cell; width: 50%; text-align: center; padding: 10px; background: #f3f4f6; border-radius: 5px; }
        .summary-box .amount { font-size: 18px; font-weight: bold; color: #ef4444; }
        .summary-box .label { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f3f4f6; padding: 8px 6px; text-align: left; font-weight: bold; border-bottom: 2px solid #ef4444; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) { background: #f9fafb; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #e5e7eb; text-align: center; color: #999; font-size: 9px; }
        @page { size: A4; margin: 15mm; }
        @media print { body { padding: 0; margin: 0; } }
    </style>
</head>
<body>
    @php
        $themeSettings = [
            'primary_color' => \App\Models\SystemSetting::get('primary_color', '#ef4444'),
            'hospital_logo' => \App\Models\SystemSetting::get('hospital_logo', ''),
            'hospital_name' => \App\Models\SystemSetting::get('hospital_name', config('app.name', 'DuncoHMS')),
            'hospital_address' => \App\Models\SystemSetting::get('hospital_address', ''),
            'hospital_phone' => \App\Models\SystemSetting::get('hospital_phone', ''),
            'hospital_email' => \App\Models\SystemSetting::get('hospital_email', ''),
        ];
    @endphp
    <div class="header">
        @if(!empty($themeSettings['hospital_logo']))
            <img src="{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
        @endif
        <h1>{{ $themeSettings['hospital_name'] }}</h1>
        <p>{{ $themeSettings['hospital_address'] }}</p>
        <p>Tel: {{ $themeSettings['hospital_phone'] }} | Email: {{ $themeSettings['hospital_email'] }}</p>
        <p class="subtitle">Disease Surveillance Report | Generated: {{ now()->format('M d, Y h:i A') }}</p>
        @if($dateFrom || $dateTo)
            <p class="subtitle">Period: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('M d, Y') : 'Start' }} - {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('M d, Y') : 'End' }}</p>
        @endif
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="amount">{{ number_format($diseases->sum('count')) }}</div>
            <div class="label">Total Diagnoses</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ number_format($diseases->count()) }}</div>
            <div class="label">Unique Diseases</div>
        </div>
    </div>

    @if($diseases->count() > 0)
    <h3 style="font-size: 14px; color: #ef4444; margin-top: 20px;">Disease Distribution</h3>
    <table>
        <thead><tr><th>#</th><th>Disease</th><th class="text-right">Count</th></tr></thead>
        <tbody>
            @foreach($diseases as $index => $disease)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $disease->diagnosis }}</td>
                    <td class="text-right">{{ number_format($disease->count) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; color: #999; padding: 20px;">No disease surveillance data available for the selected period.</p>
    @endif

    <div class="footer">
        <p>{{ $themeSettings['hospital_name'] }} | {{ $themeSettings['hospital_address'] }} | {{ $themeSettings['hospital_phone'] }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>
