<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Death Certificate - {{ $deathReport->report_number }}</title>
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
        .cause-box { padding: 15px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 5px; margin: 10px 0; }
        .cause-box .label { font-weight: bold; color: #dc2626; font-size: 10px; text-transform: uppercase; margin-bottom: 5px; }
        .cause-box .value { font-size: 14px; color: #333; }
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
            <div class="title">Death Certificate</div>
        </div>

        <div class="no-print">
            <button onclick="window.print();">Print / Save as PDF</button>
        </div>

        <div class="cert-number">
            <span>Certificate No: {{ $deathReport->report_number }}</span>
        </div>

        <div class="section-title">Deceased Information</div>
        <div class="details-grid">
            <div class="detail-item">
                <div class="label">Full Name</div>
                <div class="value">{{ $deathReport->deceased_name ?? $deathReport->patient->full_name ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Patient ID</div>
                <div class="value">{{ $deathReport->patient->patient_no ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Age at Death</div>
                <div class="value">{{ $deathReport->age_at_death ?? 'N/A' }} years</div>
            </div>
            <div class="detail-item">
                <div class="label">Gender</div>
                <div class="value">{{ ucfirst($deathReport->gender ?? $deathReport->patient->gender ?? 'N/A') }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Date of Death</div>
                <div class="value">{{ $deathReport->death_date ? $deathReport->death_date->format('F d, Y') : 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Time of Death</div>
                <div class="value">{{ $deathReport->death_time ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="cause-box">
            <div class="label">Cause of Death</div>
            <div class="value">{{ $deathReport->cause_of_death ?? 'Not specified' }}</div>
        </div>

        <div class="section-title">Place & Circumstances</div>
        <div class="details-grid">
            <div class="detail-item full-width">
                <div class="label">Place of Death</div>
                <div class="value">{{ $deathReport->place_of_death ?? 'N/A' }}</div>
            </div>
        </div>

        @if($deathReport->circumstances)
            <div class="section-title">Circumstances</div>
            <p>{{ $deathReport->circumstances }}</p>
        @endif

        <div class="section-title">Attending Medical Staff</div>
        <div class="details-grid">
            <div class="detail-item">
                <div class="label">Attending Doctor</div>
                <div class="value">Dr. {{ $deathReport->attendingDoctor->full_name ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Attending Nurse</div>
                <div class="value">{{ $deathReport->attendingNurse->full_name ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="signatures">
            <div>
                <div class="line"></div>
                <p><strong>Attending Doctor</strong></p>
                <p>Dr. {{ $deathReport->attendingDoctor->full_name ?? 'N/A' }}</p>
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

