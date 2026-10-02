<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Billing Report</title>
    <style>
        @page { size: A4; margin: 15mm; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h1 { color: #10b981; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 12px; margin: 3px 0; }
        .header .subtitle { color: #999; font-size: 10px; }
        .summary { display: table; width: 100%; margin-bottom: 20px; }
        .summary-box { display: table-cell; width: 33%; text-align: center; padding: 10px; background: #f3f4f6; border-radius: 5px; }
        .summary-box .amount { font-size: 18px; font-weight: bold; color: #10b981; }
        .summary-box .label { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f3f4f6; padding: 8px 6px; text-align: left; font-weight: bold; border-bottom: 2px solid #10b981; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) { background: #f9fafb; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #e5e7eb; text-align: center; color: #999; font-size: 9px; }
        @media print { body { padding: 0; margin: 0; } table { page-break-inside: avoid; } tr { page-break-inside: avoid; } }
    </style>
</head>
<body>
    @php
        $themeSettings = [
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
        <p class="subtitle">Billing Report | Generated: {{ now()->format('M d, Y h:i A') }}</p>
        <p class="subtitle">Period: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('M d, Y') : 'Start' }} - {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('M d, Y') : 'End' }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="amount">₦{{ number_format($totalRevenue, 2) }}</div>
            <div class="label">Total Revenue</div>
        </div>
        <div class="summary-box">
            <div class="amount">₦{{ number_format($paidRevenue, 2) }}</div>
            <div class="label">Paid Revenue</div>
        </div>
        <div class="summary-box">
            <div class="amount">₦{{ number_format($pendingRevenue, 2) }}</div>
            <div class="label">Pending Revenue</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Patient</th>
                <th>Invoice #</th>
                <th class="text-right">Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') : '-' }}</td>
                    <td>{{ $invoice->patient->full_name ?? '-' }}</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td class="text-right">₦{{ number_format($invoice->total_amount, 2) }}</td>
                    <td>{{ ucfirst($invoice->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #999;">No invoices found for the selected period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>{{ $themeSettings['hospital_name'] }} | {{ $themeSettings['hospital_address'] }} | {{ $themeSettings['hospital_phone'] }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>
