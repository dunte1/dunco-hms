<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Radiology Report - {{ $radiologyRequest->request_number }}</title>
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
        .report-section { margin-bottom: 20px; padding: 15px; background: #f9fafb; border-radius: 5px; border: 1px solid #e5e7eb; }
        .report-section h3 { color: #10b981; margin: 0 0 10px 0; font-size: 14px; border-bottom: 2px solid #10b981; padding-bottom: 5px; }
        .report-section p { margin: 5px 0; font-size: 12px; white-space: pre-line; }
        .report-section .content-area { min-height: 80px; padding: 10px; background: #fff; border: 1px solid #e5e7eb; border-radius: 3px; }
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
            .report-section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    @php
        $themeSettings = \App\Models\[ "primary_color" => \App\Models\SystemSetting::get("primary_color", "#10b981"), "hospital_logo" => \App\Models\SystemSetting::get("hospital_logo", ""), "hospital_name" => \App\Models\SystemSetting::get("hospital_name", config("app.name", "DuncoHMS")), "hospital_address" => \App\Models\SystemSetting::get("hospital_address", ""), "hospital_phone" => \AppModels\SystemSetting::get("hospital_phone", ""), "hospital_email" => \App\Models\SystemSetting::get("hospital_email", "") ];
    @endphp
    <div class="header">
        @if(!empty($themeSettings['hospital_logo']))
            <img src="data:image/png;base64,{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
        @endif
        <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }} | Email: {{ \App\Models\SystemSetting::get('hospital_email', '') }}</p>
        <div class="title">RADIOLOGY REPORT</div>
    </div>

    <div class="no-print">
        <button onclick="window.print();">Print / Save as PDF</button>
    </div>

    <div class="info-section">
        <div class="left">
            <div class="box">
                <p class="label">Report Information</p>
                <p><span class="label">Report No:</span> {{ $radiologyRequest->request_number }}</p>
                <p><span class="label">Request Date:</span> {{ $radiologyRequest->request_date->format('M d, Y') }}</p>
                <p><span class="label">Modality:</span> {{ $radiologyRequest->radiologyTest->test_name ?? 'N/A' }}</p>
                <p><span class="label">Status:</span> {{ ucfirst($radiologyRequest->status) }}</p>
            </div>
        </div>
        <div class="right">
            <div class="box">
                <p class="label">Patient & Doctor Information</p>
                <p><span class="label">Patient Name:</span> {{ $radiologyRequest->patient->full_name ?? 'N/A' }}</p>
                <p><span class="label">Patient ID:</span> {{ $radiologyRequest->patient->patient_no ?? 'N/A' }}</p>
                <p><span class="label">Requesting Doctor:</span> Dr. {{ $radiologyRequest->doctor->full_name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="report-section">
        <h3>FINDINGS</h3>
        <div class="content-area">
            {{ $radiologyRequest->findings ?? 'No findings recorded.' }}
        </div>
    </div>

    <div class="report-section">
        <h3>IMPRESSION</h3>
        <div class="content-area">
            {{ $radiologyRequest->impression ?? 'No impression recorded.' }}
        </div>
    </div>

    @if($radiologyRequest->clinical_notes)
        <div class="report-section">
            <h3>CLINICAL NOTES</h3>
            <div class="content-area">
                {{ $radiologyRequest->clinical_notes }}
            </div>
        </div>
    @endif

    <div class="signature">
        <div>
            <p><strong>Radiologist:</strong></p>
            <div style="border-bottom: 1px solid #333; margin-top: 40px;"></div>
            <p style="font-size:10px;">Name & Signature</p>
        </div>
        <div>
            <p><strong>Date:</strong> {{ now()->format('M d, Y') }}</p>
        </div>
    </div>

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>

