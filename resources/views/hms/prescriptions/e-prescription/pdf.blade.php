<!DOCTYPE html>
<html>
<head>
    <title>E-Prescription - {{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; padding: 20px; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #10b981; padding-bottom: 15px; }
        .header h1 { color: #10b981; font-size: 20px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 11px; margin: 3px 0; }
        .header .title { font-size: 16px; font-weight: bold; color: #333; margin-top: 10px; text-transform: uppercase; }
        .patient-info { margin: 20px 0; padding: 15px; background: #f9fafb; border-left: 4px solid #10b981; }
        .patient-info p { margin: 5px 0; }
        .medicines { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background: #f3f4f6; font-weight: bold; }
        .signature { margin-top: 50px; text-align: right; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }
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
        <div class="title">E-PRESCRIPTION</div>
        @if($template && $template->header_text)
            <p>{{ $template->header_text }}</p>
        @endif
    </div>

    <div class="patient-info">
        <p><strong>Patient:</strong> {{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</p>
        <p><strong>Date:</strong> {{ $prescription->prescription_date->format('M d, Y') }}</p>
        <p><strong>Doctor:</strong> Dr. {{ $prescription->doctor->first_name }} {{ $prescription->doctor->last_name }}</p>
    </div>

    @if($prescription->diagnosis)
        <div style="margin: 15px 0;">
            <p><strong>Diagnosis:</strong> {{ $prescription->diagnosis }}</p>
        </div>
    @endif

    <div class="medicines">
        <h3 style="color: #10b981; margin-bottom: 10px;">Medicines</h3>
        <table>
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Dosage</th>
                    <th>Frequency</th>
                    <th>Quantity</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescription->items as $item)
                    <tr>
                        <td>{{ $item->medicine->name }}</td>
                        <td>{{ $item->dosage }}</td>
                        <td>{{ $item->frequency }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->duration_days }} days</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($prescription->digital_signature)
        <div class="signature">
            <img src="{{ $prescription->digital_signature }}" alt="Signature" style="max-height: 80px;">
            <p>Dr. {{ $prescription->doctor->first_name }} {{ $prescription->doctor->last_name }}</p>
            <p>{{ $prescription->signed_at->format('M d, Y') }}</p>
        </div>
    @endif

    @if($template && $template->footer_text)
        <div style="margin-top: 30px; text-align: center; font-size: 12px;">
            <p>{{ $template->footer_text }}</p>
        </div>
    @endif

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} &mdash; {{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
    </div>
</body>
</html>


