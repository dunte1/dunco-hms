<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payroll Summary</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h2 { color: #10b981; margin: 5px 0; }
        .header p { color: #666; font-size: 11px; margin: 3px 0; }
        .summary { margin-bottom: 20px; padding: 10px; background: #f0fdf4; border-radius: 5px; }
        .summary p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #f0fdf4; font-weight: bold; border-bottom: 2px solid #10b981; }
        tr:nth-child(even) { background: #f9fafb; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
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
        <h2>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h2>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }} | Email: {{ \App\Models\SystemSetting::get('hospital_email', '') }}</p>
        <h2>Payroll Summary</h2>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
    </div>
    
    <div class="summary">
        <p><strong>Total Gross:</strong> {{ \App\Models\SystemSetting::get('currency_symbol', '$') }}{{ number_format($summary['total_gross'], 2) }}</p>
        <p><strong>Total Net:</strong> {{ \App\Models\SystemSetting::get('currency_symbol', '$') }}{{ number_format($summary['total_net'], 2) }}</p>
        <p><strong>Total Deductions:</strong> {{ \App\Models\SystemSetting::get('currency_symbol', '$') }}{{ number_format($summary['total_deductions'], 2) }}</p>
        <p><strong>Count:</strong> {{ $summary['count'] }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Pay Date</th>
                <th>Gross Salary</th>
                <th>Deductions</th>
                <th>Net Salary</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->employee->full_name }}</td>
                    <td>{{ $payroll->pay_date->format('M d, Y') }}</td>
                    <td>{{ \App\Models\SystemSetting::get('currency_symbol', '$') }}{{ number_format($payroll->gross_salary, 2) }}</td>
                    <td>{{ \App\Models\SystemSetting::get('currency_symbol', '$') }}{{ number_format($payroll->deductions, 2) }}</td>
                    <td>{{ \App\Models\SystemSetting::get('currency_symbol', '$') }}{{ number_format($payroll->net_salary, 2) }}</td>
                    <td>{{ ucfirst($payroll->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>
