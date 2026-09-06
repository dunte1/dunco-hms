<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Discharge Summary - {{ $discharge->patient->full_name ?? 'Patient' }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h1 { color: #10b981; font-size: 20px; margin: 5px 0; }
        .header p { color: #666; font-size: 11px; margin: 3px 0; }
        .header .title { font-size: 16px; font-weight: bold; color: #333; margin-top: 10px; text-transform: uppercase; }
        .info-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-section .left, .info-section .right { width: 48%; }
        .info-section .box { padding: 12px; background: #f9fafb; border-radius: 5px; border-left: 4px solid #10b981; margin-bottom: 10px; }
        .info-section p { margin: 4px 0; font-size: 11px; }
        .info-section .label { font-weight: bold; color: #555; }
        .report-section { margin-bottom: 15px; }
        .report-section h3 { color: #10b981; font-size: 13px; margin: 0 0 8px 0; border-bottom: 2px solid #10b981; padding-bottom: 4px; text-transform: uppercase; }
        .report-section .content { padding: 10px; background: #f9fafb; border-radius: 5px; font-size: 11px; white-space: pre-line; min-height: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 11px; }
        th { background-color: #f0fdf4; font-weight: bold; border-bottom: 2px solid #10b981; }
        tr:nth-child(even) { background: #f9fafb; }
        .signature { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature div { width: 45%; }
        .signature p { font-size: 11px; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
        .no-print { margin: 20px 0; text-align: center; }
        .no-print button { background: #10b981; color: #fff; border: none; padding: 10px 30px; border-radius: 5px; cursor: pointer; font-size: 14px; }
        @media print {
            @page { size: A4; margin: 15mm; }
            body { padding: 0; margin: 0; }
            .no-print { display: none !important; }
            table { page-break-inside: avoid; }
            tr { page-break-inside: avoid; }
            .report-section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    @php
        $themeSettings = \App\Models\SystemSetting::getThemeSettings();
    @endphp
    <div class="header">
        @if(!empty($themeSettings['hospital_logo']))
            <img src="data:image/png;base64,{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
        @endif
        <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }} | Email: {{ \App\Models\SystemSetting::get('hospital_email', '') }}</p>
        <div class="title">DISCHARGE SUMMARY</div>
    </div>

    <div class="no-print">
        <button onclick="window.print();">Print / Save as PDF</button>
    </div>

    <div class="info-section">
        <div class="left">
            <div class="box">
                <p class="label">Patient Information</p>
                <p><span class="label">Name:</span> {{ $discharge->patient->full_name ?? 'N/A' }}</p>
                <p><span class="label">Patient ID:</span> {{ $discharge->patient->patient_no ?? 'N/A' }}</p>
                <p><span class="label">Age:</span> {{ $discharge->patient->dob ? \Carbon\Carbon::parse($discharge->patient->dob)->age . ' years' : 'N/A' }}</p>
                <p><span class="label">Gender:</span> {{ ucfirst($discharge->patient->gender ?? 'N/A') }}</p>
            </div>
        </div>
        <div class="right">
            <div class="box">
                <p class="label">Admission Details</p>
                <p><span class="label">Doctor:</span> Dr. {{ $discharge->doctor->full_name ?? 'N/A' }}</p>
                <p><span class="label">Admission Date:</span> {{ $discharge->admission_date ? $discharge->admission_date->format('M d, Y H:i') : 'N/A' }}</p>
                <p><span class="label">Discharge Date:</span> {{ $discharge->discharge_date ? $discharge->discharge_date->format('M d, Y H:i') : 'N/A' }}</p>
                <p><span class="label">Bed/Ward:</span> {{ $discharge->bed->ward ?? '' }} {{ $discharge->bed->bed_number ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="report-section">
        <h3>Diagnosis</h3>
        <div class="content">{{ $discharge->diagnosis ?? 'No diagnosis recorded.' }}</div>
    </div>

    <div class="report-section">
        <h3>Treatment Given</h3>
        <div class="content">{{ $discharge->treatment_plan ?? 'No treatment details recorded.' }}</div>
    </div>

    <div class="report-section">
        <h3>Condition at Discharge</h3>
        <div class="content">{{ $discharge->condition_at_discharge ?? $discharge->status ?? 'Stable' }}</div>
    </div>

    @if(!empty($discharge->medications_on_discharge))
        <div class="report-section">
            <h3>Medications on Discharge</h3>
            <div class="content">{{ $discharge->medications_on_discharge }}</div>
        </div>
    @endif

    @if(!empty($discharge->discharge_advice))
        <div class="report-section">
            <h3>Discharge Advice</h3>
            <div class="content">{{ $discharge->discharge_advice }}</div>
        </div>
    @endif

    @if(!empty($discharge->follow_up_instructions))
        <div class="report-section">
            <h3>Follow-up Instructions</h3>
            <div class="content">{{ $discharge->follow_up_instructions }}</div>
        </div>
    @endif

    <div class="report-section">
        <h3>Duration of Stay</h3>
        <div class="content">
            @if($discharge->admission_date && $discharge->discharge_date)
                {{ \Carbon\Carbon::parse($discharge->admission_date)->diffForHumans($discharge->discharge_date, true) }}
            @else
                N/A
            @endif
        </div>
    </div>

    <div class="signature">
        <div>
            <p><strong>Discharging Doctor:</strong></p>
            <div style="border-bottom: 1px solid #333; margin-top: 40px;"></div>
            <p style="font-size:10px;">Dr. {{ $discharge->doctor->full_name ?? 'N/A' }} / Date</p>
        </div>
        <div>
            <p><strong>Patient/Guardian:</strong></p>
            <div style="border-bottom: 1px solid #333; margin-top: 40px;"></div>
            <p style="font-size:10px;">Name & Signature / Date</p>
        </div>
    </div>

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>
