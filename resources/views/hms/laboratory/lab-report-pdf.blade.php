<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lab Report - {{ $labRequest->request_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h1 { color: #10b981; font-size: 20px; margin: 5px 0; }
        .header p { color: #666; font-size: 11px; margin: 3px 0; }
        .header .title { font-size: 16px; font-weight: bold; color: #333; margin-top: 10px; text-transform: uppercase; }
        .patient-info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .patient-info .left, .patient-info .right { width: 48%; }
        .patient-info .box { padding: 12px; background: #f9fafb; border-radius: 5px; border-left: 4px solid #10b981; margin-bottom: 10px; }
        .patient-info p { margin: 4px 0; font-size: 11px; }
        .patient-info .label { font-weight: bold; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #f0fdf4; font-weight: bold; border-bottom: 2px solid #10b981; }
        tr:nth-child(even) { background: #f9fafb; }
        .text-right { text-align: right; }
        .status-normal { color: #10b981; font-weight: bold; }
        .status-abnormal { color: #ef4444; font-weight: bold; }
        .notes-section { margin-top: 20px; padding: 12px; background: #f9fafb; border-radius: 5px; }
        .notes-section h4 { color: #10b981; margin: 0 0 8px 0; font-size: 12px; }
        .notes-section p { margin: 4px 0; font-size: 11px; }
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
        <div class="title">LABORATORY REPORT</div>
    </div>

    <div class="no-print">
        <button onclick="window.print();">Print / Save as PDF</button>
    </div>

    <div class="patient-info">
        <div class="left">
            <div class="box">
                <p class="label">Report Information</p>
                <p><span class="label">Report No:</span> {{ $labRequest->request_number }}</p>
                <p><span class="label">Request Date:</span> {{ $labRequest->request_date->format('M d, Y') }}</p>
                <p><span class="label">Status:</span> {{ ucfirst($labRequest->status) }}</p>
                <p><span class="label">Requesting Doctor:</span> Dr. {{ $labRequest->doctor->full_name ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="right">
            <div class="box">
                <p class="label">Patient Information</p>
                <p><span class="label">Name:</span> {{ $labRequest->patient->full_name ?? 'N/A' }}</p>
                <p><span class="label">Patient ID:</span> {{ $labRequest->patient->patient_no ?? 'N/A' }}</p>
                <p><span class="label">Age:</span> {{ $labRequest->patient->dob ? \Carbon\Carbon::parse($labRequest->patient->dob)->age . ' years' : 'N/A' }}</p>
                <p><span class="label">Gender:</span> {{ ucfirst($labRequest->patient->gender ?? 'N/A') }}</p>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:25%">Test Name</th>
                <th style="width:20%">Result</th>
                <th style="width:20%">Reference Range</th>
                <th style="width:15%">Unit</th>
                <th style="width:15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($labRequest->items as $index => $item)
                @php
                    $isAbnormal = isset($item->status) && $item->status === 'abnormal';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->labTest->test_name ?? 'N/A' }}</td>
                    <td><strong>{{ $item->result_value ?? 'Pending' }}</strong></td>
                    <td>{{ $item->labTest->normal_range ?? 'N/A' }}</td>
                    <td>{{ $item->unit ?? '-' }}</td>
                    <td class="{{ $isAbnormal ? 'status-abnormal' : 'status-normal' }}">
                        {{ $item->status ? ucfirst($item->status) : ($item->result_value ? 'Normal' : 'Pending') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No test results found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($labRequest->results_notes)
        <div class="notes-section">
            <h4>Notes / Comments</h4>
            <p>{{ $labRequest->results_notes }}</p>
        </div>
    @endif

    <div class="signature">
        <div>
            <p><strong>Reviewed By:</strong></p>
            <div style="border-bottom: 1px solid #333; margin-top: 40px;"></div>
            <p style="font-size:10px;">Doctor's Name & Signature</p>
        </div>
        <div>
            <p><strong>Lab Technician:</strong></p>
            <div style="border-bottom: 1px solid #333; margin-top: 40px;"></div>
            <p style="font-size:10px;">Technician's Name & Signature</p>
        </div>
    </div>

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>
