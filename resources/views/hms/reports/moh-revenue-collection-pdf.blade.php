<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Revenue Collection Report</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #f59e0b; }
        .header h1 { color: #f59e0b; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 12px; margin: 3px 0; }
        .header .subtitle { color: #999; font-size: 10px; }
        .summary { display: table; width: 100%; margin-bottom: 20px; }
        .summary-box { display: table-cell; width: 25%; text-align: center; padding: 10px; background: #f3f4f6; border-radius: 5px; }
        .summary-box .amount { font-size: 18px; font-weight: bold; color: #f59e0b; }
        .summary-box .label { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f3f4f6; padding: 8px 6px; text-align: left; font-weight: bold; border-bottom: 2px solid #f59e0b; font-size: 10px; }
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
            'primary_color' => \App\Models\SystemSetting::get('primary_color', '#f59e0b'),
            'hospital_logo' => \App\Models\SystemSetting::get('hospital_logo', ''),
            'hospital_name' => \App\Models\SystemSetting::get('hospital_name', config('app.name', 'DuncoHMS')),
            'hospital_address' => \App\Models\SystemSetting::get('hospital_address', ''),
            'hospital_phone' => \App\Models\SystemSetting::get('hospital_phone', ''),
            'hospital_email' => \App\Models\SystemSetting::get('hospital_email', ''),
            'currency_symbol' => \App\Models\SystemSetting::get('currency_symbol', 'KES'),
        ];
    @endphp
    <div class="header">
        @if(!empty($themeSettings['hospital_logo']))
            <img src="{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
        @endif
        <h1>{{ $themeSettings['hospital_name'] }}</h1>
        <p>{{ $themeSettings['hospital_address'] }}</p>
        <p>Tel: {{ $themeSettings['hospital_phone'] }} | Email: {{ $themeSettings['hospital_email'] }}</p>
        <p class="subtitle">Revenue Collection Report | Generated: {{ now()->format('M d, Y h:i A') }}</p>
        @if($dateFrom || $dateTo)
            <p class="subtitle">Period: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('M d, Y') : 'Start' }} - {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('M d, Y') : 'End' }}</p>
        @endif
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="amount">{{ $themeSettings['currency_symbol'] }} {{ number_format($totalCollected, 2) }}</div>
            <div class="label">Total Collected</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ $themeSettings['currency_symbol'] }} {{ number_format($outstandingBalance, 2) }}</div>
            <div class="label">Outstanding</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ $byMethod->count() }}</div>
            <div class="label">Payment Methods</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ $byDepartment->count() }}</div>
            <div class="label">Departments</div>
        </div>
    </div>

    @if($byMethod->count() > 0)
    <h3 style="font-size: 14px; color: #f59e0b; margin-top: 20px;">Payments by Method</h3>
    <table>
        <thead><tr><th>Method</th><th class="text-right">Amount</th></tr></thead>
        <tbody>
            @foreach($byMethod as $method)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $method->payment_method ?? 'N/A')) }}</td>
                    <td class="text-right">{{ $themeSettings['currency_symbol'] }} {{ number_format($method->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($byDepartment->count() > 0)
    <h3 style="font-size: 14px; color: #f59e0b; margin-top: 20px;">Revenue by Department</h3>
    <table>
        <thead><tr><th>Department</th><th class="text-right">Amount</th></tr></thead>
        <tbody>
            @foreach($byDepartment as $dept)
                <tr>
                    <td>{{ $dept->department_name ?? 'N/A' }}</td>
                    <td class="text-right">{{ $themeSettings['currency_symbol'] }} {{ number_format($dept->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>{{ $themeSettings['hospital_name'] }} | {{ $themeSettings['hospital_address'] }} | {{ $themeSettings['hospital_phone'] }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>
