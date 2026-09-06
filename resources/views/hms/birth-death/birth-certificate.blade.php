<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Birth Certificate - {{ $birthReport->report_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; line-height: 1.5; }
        .certificate { border: 4px double #10b981; padding: 30px; max-width: 700px; margin: 0 auto; position: relative; }
        .certificate::before { content: ''; position: absolute; top: 8px; left: 8px; right: 8px; bottom: 8px; border: 1px solid #10b981; pointer-events: none; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h1 { color: #10b981; font-size: 20px; margin: 5px 0; }
        .header p { color: #666; font-size: 11px; margin: 3px 0; }
        .header .title { font-size: 22px; font-weight: bold; color: #10b981; margin-top: 10px; text-transform: uppercase; letter-spacing: 2px; }
        .cert-number { text-align: center; margin-bottom: 15px; }
        .cert-number span { background: #10b981; color: #fff; padding: 5px 20px; border-radius: 3px; font-weight: bold; font-size: 11px; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0; }
        .detail-item { padding: 8px; border-bottom: 1px dashed #ccc; }
        .detail-item .label { font-weight: bold; color: #10b981; font-size: 10px; text-transform: uppercase; }
        .detail-item .value { font-size: 13px; margin-top: 3px; }
        .full-width { grid-column: 1 / -1; }
        .section-title { color: #10b981; font-size: 14px; font-weight: bold; margin: 20px 0 10px 0; border-bottom: 2px solid #10b981; padding-bottom: 5px; text-transform: uppercase; }
        .weight-length { display: flex; gap: 40px; justify-content: center; margin: 15px 0; padding: 15px; background: #f0fdf4; border-radius: 5px; }
        .weight-length div { text-align: center; }
        .weight-length .big { font-size: 20px; font-weight: bold; color: #10b981; }
        .weight-length .small { font-size: 10px; color: #666; }
        .doctor-info { margin-top: 20px; padding: 10px; background: #f9fafb; border-radius: 5px; text-align: center; }
        .doctor-info p { margin: 3px 0; font-size: 11px; }
        .signatures { display: flex; justify-content: space-between; margin-top: 40px; padding-top: 20px; }
        .signatures div { text-align: center; width: 30%; }
        .signatures .line { border-top: 1px solid #333; margin-top: 40px; padding-top: 5px; }
        .signatures p { font-size: 10px; margin: 3px 0; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #94a3b8; }
        .no-print { margin: 20px 0; text-align: center; }
        .no-print button { background: #10b981; color: #fff; border: none; padding: 10px 30px; border-radius: 5px; cursor: pointer; font-size: 14px; }
        @media print {
            @page { size: A4; margin: 15mm; }
            body { padding: 0; margin: 0; }
            .no-print { display: none !important; }
            .certificate { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    @php
        $themeSettings = \App\Models\[ "primary_color" => \App\Models\SystemSetting::get("primary_color", "#10b981"), "hospital_logo" => \App\Models\SystemSetting::get("hospital_logo", ""), "hospital_name" => \App\Models\SystemSetting::get("hospital_name", config("app.name", "DuncoHMS")), "hospital_address" => \App\Models\SystemSetting::get("hospital_address", ""), "hospital_phone" => \AppModels\SystemSetting::get("hospital_phone", ""), "hospital_email" => \App\Models\SystemSetting::get("hospital_email", "") ];
    @endphp
    <div class="certificate">
        <div class="header">
            @if(!empty($themeSettings['hospital_logo']))
                <img src="data:image/png;base64,{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
            @endif
            <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
            <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
            <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
            <div class="title">Birth Certificate</div>
        </div>

        <div class="no-print">
            <button onclick="window.print();">Print / Save as PDF</button>
        </div>

        <div class="cert-number">
            <span>Certificate No: {{ $birthReport->report_number }}</span>
        </div>

        <div class="weight-length">
            <div>
                <div class="big">{{ $birthReport->birth_weight ?? 'N/A' }} kg</div>
                <div class="small">Birth Weight</div>
            </div>
            <div>
                <div class="big">{{ $birthReport->birth_length ?? 'N/A' }} cm</div>
                <div class="small">Birth Length</div>
            </div>
        </div>

        <div class="section-title">Child Information</div>
        <div class="details-grid">
            <div class="detail-item">
                <div class="label">Baby's Name</div>
                <div class="value">{{ $birthReport->baby_name ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Gender</div>
                <div class="value">{{ ucfirst($birthReport->gender ?? 'N/A') }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Date of Birth</div>
                <div class="value">{{ $birthReport->birth_date ? $birthReport->birth_date->format('F d, Y') : 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Time of Birth</div>
                <div class="value">{{ $birthReport->birth_time ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="section-title">Parent Information</div>
        <div class="details-grid">
            <div class="detail-item">
                <div class="label">Mother's Name</div>
                <div class="value">{{ $birthReport->mother_name ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Mother's Phone</div>
                <div class="value">{{ $birthReport->mother_phone ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Father's Name</div>
                <div class="value">{{ $birthReport->father_name ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Father's Phone</div>
                <div class="value">{{ $birthReport->father_phone ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="section-title">Delivery Information</div>
        <div class="details-grid">
            <div class="detail-item">
                <div class="label">Delivery Type</div>
                <div class="value">{{ ucfirst(str_replace('_', ' ', $birthReport->delivery_type ?? 'N/A')) }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Attending Doctor</div>
                <div class="value">Dr. {{ $birthReport->attendingDoctor->full_name ?? 'N/A' }}</div>
            </div>
        </div>

        @if($birthReport->complications)
            <div class="section-title">Complications</div>
            <p>{{ $birthReport->complications }}</p>
        @endif

        <div class="signatures">
            <div>
                <div class="line"></div>
                <p><strong>Attending Doctor</strong></p>
                <p>Dr. {{ $birthReport->attendingDoctor->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <div class="line"></div>
                <p><strong>Hospital Administrator</strong></p>
                <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</p>
            </div>
        </div>

        <div class="footer">
            <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
            <p>This is a computer-generated document. Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
        </div>
    </div>
</body>
</html>

