<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; line-height: 1.5; }
        .certificate { border: 4px double #10b981; padding: 40px; max-width: 750px; margin: 0 auto; position: relative; text-align: center; }
        .certificate::before { content: ''; position: absolute; top: 10px; left: 10px; right: 10px; bottom: 10px; border: 1px solid #10b981; pointer-events: none; }
        .certificate::after { content: ''; position: absolute; top: 15px; left: 15px; right: 15px; bottom: 15px; border: 1px dashed #10b981; pointer-events: none; }
        .header { margin-bottom: 20px; position: relative; z-index: 1; }
        .header h1 { color: #10b981; font-size: 18px; margin: 5px 0; }
        .header p { color: #666; font-size: 11px; margin: 3px 0; }
        .cert-title { font-size: 28px; font-weight: bold; color: #10b981; text-transform: uppercase; letter-spacing: 3px; margin: 20px 0; position: relative; z-index: 1; }
        .cert-title::after { content: ''; display: block; width: 100px; height: 3px; background: #10b981; margin: 10px auto 0; }
        .cert-text { font-size: 14px; margin: 15px 0; position: relative; z-index: 1; }
        .cert-text .name { font-size: 24px; font-weight: bold; color: #10b981; margin: 10px 0; display: block; border-bottom: 2px solid #10b981; display: inline-block; padding-bottom: 5px; }
        .cert-program { font-size: 16px; font-weight: bold; color: #333; margin: 15px 0; position: relative; z-index: 1; }
        .cert-details { margin: 25px auto; max-width: 500px; position: relative; z-index: 1; }
        .cert-details table { width: 100%; margin: 0 auto; }
        .cert-details td { padding: 6px 12px; font-size: 12px; text-align: left; border: none; }
        .cert-details td:first-child { font-weight: bold; color: #10b981; width: 40%; text-align: right; }
        .cert-details td:last-child { color: #333; width: 60%; text-align: left; }
        .cert-details tr:nth-child(odd) { background: #f0fdf4; border-radius: 3px; }
        .signatures { display: flex; justify-content: space-between; margin-top: 40px; padding: 0 40px; position: relative; z-index: 1; }
        .signatures div { text-align: center; width: 35%; }
        .signatures .line { border-top: 1px solid #333; margin-top: 40px; padding-top: 5px; }
        .signatures p { font-size: 10px; margin: 3px 0; }
        .seal { margin-top: 20px; position: relative; z-index: 1; }
        .seal .stamp { display: inline-block; width: 80px; height: 80px; border: 3px solid #10b981; border-radius: 50%; line-height: 80px; color: #10b981; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #94a3b8; position: relative; z-index: 1; }
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
        $themeSettings = [
            'primary_color' => \App\Models\SystemSetting::get('primary_color', '#10b981'),
            'hospital_logo' => \App\Models\SystemSetting::get('hospital_logo', ''),
            'hospital_name' => \App\Models\SystemSetting::get('hospital_name', config('app.name', 'DuncoHMS')),
            'hospital_address' => \App\Models\SystemSetting::get('hospital_address', ''),
            'hospital_phone' => \App\Models\SystemSetting::get('hospital_phone', ''),
            'hospital_email' => \App\Models\SystemSetting::get('hospital_email', ''),
        ];
    @endphp
    <div class="certificate">
        <div class="header">
            @if(!empty($themeSettings['hospital_logo']))
                <img src="data:image/png;base64,{{ $themeSettings['hospital_logo'] }}" style="height: 50px; margin-right: 10px;">
            @endif
            <h1>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
            <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
            <p>Tel: {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        </div>

        <div class="no-print">
            <button onclick="window.print();">Print / Save as PDF</button>
        </div>

        <div class="cert-title">Certificate of Completion</div>

        <div class="cert-text">
            This is to certify that
            <span class="name">{{ $enrollment->employee->full_name ?? 'N/A' }}</span>
            has successfully completed the training program
        </div>

        <div class="cert-program">"{{ $enrollment->trainingProgram->title ?? 'N/A' }}"</div>

        <div class="cert-details">
            <table>
                <tr>
                    <td>Employee ID:</td>
                    <td>{{ $enrollment->employee->employee_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Department:</td>
                    <td>{{ $enrollment->employee->department->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Training Category:</td>
                    <td>{{ ucfirst($enrollment->trainingProgram->category ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td>Start Date:</td>
                    <td>{{ $enrollment->trainingProgram->start_date ? $enrollment->trainingProgram->start_date->format('F d, Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>End Date:</td>
                    <td>{{ $enrollment->trainingProgram->end_date ? $enrollment->trainingProgram->end_date->format('F d, Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Duration:</td>
                    <td>{{ $enrollment->trainingProgram->duration_hours ?? $enrollment->attendance_hours ?? 'N/A' }} hours</td>
                </tr>
                <tr>
                    <td>Location:</td>
                    <td>{{ $enrollment->trainingProgram->location ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Instructor:</td>
                    <td>{{ $enrollment->trainingProgram->instructor ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="seal">
            <div class="stamp">Official<br>Seal</div>
        </div>

        <div class="signatures">
            <div>
                <div class="line"></div>
                <p><strong>Employee Signature</strong></p>
                <p>{{ $enrollment->employee->full_name ?? '' }}</p>
            </div>
            <div>
                <div class="line"></div>
                <p><strong>Instructor Signature</strong></p>
                <p>{{ $enrollment->trainingProgram->instructor ?? '' }}</p>
            </div>
        </div>

        <div class="footer">
            <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
            <p>Certificate Issued on {{ now()->format('F d, Y') }}</p>
        </div>
    </div>
</body>
</html>



