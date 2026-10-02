<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pharmacy Consumption Report</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h1 { color: #10b981; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 12px; margin: 3px 0; }
        .header .subtitle { color: #999; font-size: 10px; }
        .summary { display: table; width: 100%; margin-bottom: 20px; }
        .summary-box { display: table-cell; width: 25%; text-align: center; padding: 10px; background: #f3f4f6; border-radius: 5px; }
        .summary-box .amount { font-size: 18px; font-weight: bold; color: #10b981; }
        .summary-box .label { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f3f4f6; padding: 8px 6px; text-align: left; font-weight: bold; border-bottom: 2px solid #10b981; font-size: 10px; }
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
            'primary_color' => \App\Models\SystemSetting::get('primary_color', '#10b981'),
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
        <p class="subtitle">Pharmacy Consumption Report | Generated: {{ now()->format('M d, Y h:i A') }}</p>
        @if($dateFrom || $dateTo)
            <p class="subtitle">Period: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('M d, Y') : 'Start' }} - {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('M d, Y') : 'End' }}</p>
        @endif
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="amount">{{ number_format($totalPrescriptions) }}</div>
            <div class="label">Total Prescriptions</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ number_format($totalMedicinesDispensed) }}</div>
            <div class="label">Medicines Dispensed</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ number_format($topMedicines->count()) }}</div>
            <div class="label">Top Medicines</div>
        </div>
        <div class="summary-box">
            <div class="amount">{{ number_format($expiringMedicines ?? 0) }}</div>
            <div class="label">Expiring (30d)</div>
        </div>
    </div>

    @if($topMedicines->count() > 0)
    <h3 style="font-size: 14px; color: #10b981; margin-top: 20px;">Top Medicines</h3>
    <table>
        <thead><tr><th>#</th><th>Medicine</th><th class="text-right">Quantity Dispensed</th></tr></thead>
        <tbody>
            @foreach($topMedicines as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->medicine->name ?? 'Medicine #' . $item->medicine_id }}</td>
                    <td class="text-right">{{ number_format($item->total_qty) }}</td>
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
