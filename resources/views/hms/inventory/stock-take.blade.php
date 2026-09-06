<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Take Sheet</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h1 { color: #10b981; font-size: 20px; margin: 5px 0; }
        .header p { color: #666; font-size: 11px; margin: 3px 0; }
        .header .title { font-size: 16px; font-weight: bold; color: #333; margin-top: 10px; text-transform: uppercase; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 15px; padding: 10px; background: #f0fdf4; border-radius: 5px; }
        .info-row p { margin: 3px 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #f0fdf4; font-weight: bold; border-bottom: 2px solid #10b981; }
        tr:nth-child(even) { background: #f9fafb; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
        .no-print { margin: 20px 0; text-align: center; }
        .no-print button { background: #10b981; color: #fff; border: none; padding: 10px 30px; border-radius: 5px; cursor: pointer; font-size: 14px; }
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
            <img src="data:image/png;base64,{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
        @endif
        <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }} | Email: {{ \App\Models\SystemSetting::get('hospital_email', '') }}</p>
        <div class="title">STOCK TAKE SHEET</div>
    </div>

    <div class="info-row">
        <div>
            <p><strong>Date:</strong> {{ now()->format('M d, Y') }}</p>
            <p><strong>Stock Taker:</strong> {{ $stockTaker ?? auth()->user()->name ?? 'N/A' }}</p>
        </div>
        <div>
            <p><strong>Department:</strong> {{ $department ?? 'N/A' }}</p>
            <p><strong>Reference:</strong> STK-{{ now()->format('Ymd-His') }}</p>
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print();">Print / Save as PDF</button>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:25%">Medicine Name</th>
                <th style="width:15%">Category</th>
                <th style="width:12%" class="text-right">Expected Qty</th>
                <th style="width:12%" class="text-right">Actual Qty</th>
                <th style="width:12%" class="text-right">Variance</th>
                <th style="width:19%">Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockItems as $index => $item)
                @php
                    $expected = $item->quantity ?? 0;
                    $actual = $item->actual_quantity ?? null;
                    $variance = $actual !== null ? $actual - $expected : '';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->medicine->name ?? $item->name ?? 'N/A' }}{{ isset($item->medicine->strength) && $item->medicine->strength ? " ({$item->medicine->strength})" : '' }}</td>
                    <td>{{ $item->medicine->category->name ?? $item->category ?? 'N/A' }}</td>
                    <td class="text-right">{{ $expected }}</td>
                    <td class="text-right">{{ $actual !== null ? $actual : '________' }}</td>
                    <td class="text-right">{{ $variance !== '' ? $variance : '________' }}</td>
                    <td>{{ $item->notes ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No stock items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 40px; display: flex; justify-content: space-between;">
        <div>
            <p><strong>Stock Taker Signature:</strong></p>
            <div style="border-bottom: 1px solid #333; width: 200px; margin-top: 40px;"></div>
        </div>
        <div>
            <p><strong>Supervisor Signature:</strong></p>
            <div style="border-bottom: 1px solid #333; width: 200px; margin-top: 40px;"></div>
        </div>
    </div>

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>



