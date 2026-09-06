<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report: {{ $template->name }}</title>
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
        $themeSettings = \App\Models\[ "primary_color" => \App\Models\SystemSetting::get("primary_color", "#10b981"), "hospital_logo" => \App\Models\SystemSetting::get("hospital_logo", ""), "hospital_name" => \App\Models\SystemSetting::get("hospital_name", config("app.name", "DuncoHMS")), "hospital_address" => \App\Models\SystemSetting::get("hospital_address", ""), "hospital_phone" => \AppModels\SystemSetting::get("hospital_phone", ""), "hospital_email" => \App\Models\SystemSetting::get("hospital_email", "") ];
    @endphp
    <div class="header">
        @if(isset($themeSettings) && !empty($themeSettings['hospital_logo']))
            <img src="data:image/png;base64,{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
        @endif
        <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }} | Email: {{ \App\Models\SystemSetting::get('hospital_email', '') }}</p>
        <p class="subtitle">{{ $template->name }} | Generated: {{ now()->format('M d, Y h:i A') }}</p>
    </div>

    @if(!empty($data))
    <table>
        <thead>
            <tr>
                @foreach($columns as $col)
                    <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                @foreach($columns as $col)
                    <td>{{ $row->$col ?? ($row[$col] ?? 'N/A') }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p style="text-align: center; color: #999;">No data available for this report.</p>
    @endif

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>

