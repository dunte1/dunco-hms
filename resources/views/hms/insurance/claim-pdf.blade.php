<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Insurance Claim - {{ $claim->claim_number }}</title>
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
        .claim-summary { display: flex; justify-content: space-between; margin-bottom: 20px; gap: 15px; }
        .claim-box { flex: 1; text-align: center; padding: 15px; background: #f0fdf4; border-radius: 5px; border: 1px solid #d1fae5; }
        .claim-box .amount { font-size: 18px; font-weight: bold; color: #10b981; }
        .claim-box .label { font-size: 10px; color: #666; margin-top: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #f0fdf4; font-weight: bold; border-bottom: 2px solid #10b981; }
        tr:nth-child(even) { background: #f9fafb; }
        .text-right { text-align: right; }
        .totals-box { float: right; width: 300px; margin-top: 10px; }
        .totals-box table { width: 100%; }
        .totals-box td { border: none; padding: 4px 8px; font-size: 11px; }
        .totals-box tr.total-row td { border-top: 2px solid #10b981; font-weight: bold; font-size: 13px; color: #10b981; }
        .docs-list { clear: both; margin-top: 20px; padding: 12px; background: #f9fafb; border-radius: 5px; }
        .docs-list h4 { color: #10b981; margin: 0 0 8px 0; font-size: 12px; }
        .docs-list ul { margin: 5px 0; padding-left: 20px; font-size: 11px; }
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
        <div class="title">INSURANCE CLAIM</div>
    </div>

    <div class="no-print">
        <button onclick="window.print();">Print / Save as PDF</button>
    </div>

    <div class="info-section">
        <div class="left">
            <div class="box">
                <p class="label">Claim Information</p>
                <p><span class="label">Claim Number:</span> {{ $claim->claim_number }}</p>
                <p><span class="label">Claim Date:</span> {{ $claim->claim_date ? $claim->claim_date->format('M d, Y') : 'N/A' }}</p>
                <p><span class="label">Service Date:</span> {{ $claim->service_date ? $claim->service_date->format('M d, Y') : 'N/A' }}</p>
                <p><span class="label">Status:</span> {{ ucfirst(str_replace('_', ' ', $claim->status)) }}</p>
            </div>
        </div>
        <div class="right">
            <div class="box">
                <p class="label">Patient & Insurance</p>
                <p><span class="label">Patient Name:</span> {{ $claim->patient->full_name ?? 'N/A' }}</p>
                <p><span class="label">Patient ID:</span> {{ $claim->patient->patient_no ?? 'N/A' }}</p>
                <p><span class="label">Insurance Provider:</span> {{ $claim->patientInsurance->insuranceProvider->name ?? $claim->patientInsurance->provider->name ?? 'N/A' }}</p>
                <p><span class="label">Policy Number:</span> {{ $claim->patientInsurance->policy_number ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="claim-summary">
        <div class="claim-box">
            <div class="amount">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($claim->claimed_amount, 2) }}</div>
            <div class="label">Claimed Amount</div>
        </div>
        <div class="claim-box">
            <div class="amount">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($claim->approved_amount ?? 0, 2) }}</div>
            <div class="label">Approved Amount</div>
        </div>
        <div class="claim-box">
            <div class="amount">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($claim->paid_amount ?? 0, 2) }}</div>
            <div class="label">Paid Amount</div>
        </div>
    </div>

    @if($claim->invoice && $claim->invoice->items)
        <table>
            <thead>
                <tr>
                    <th style="width:5%">#</th>
                    <th style="width:40%">Service / Item</th>
                    <th style="width:15%">Qty</th>
                    <th style="width:20%" class="text-right">Unit Price</th>
                    <th style="width:20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($claim->invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->description ?? $item->name ?? 'Service' }}</td>
                        <td>{{ $item->quantity ?? 1 }}</td>
                        <td class="text-right">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($item->unit_price ?? 0, 2) }}</td>
                        <td class="text-right">{{ \App\Models\SystemSetting::get('currency_symbol', 'KSh') }}{{ number_format($item->total ?? ($item->quantity * ($item->unit_price ?? 0)), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($claim->diagnosis_code || $claim->diagnosis_description)
        <div style="margin-top: 15px; padding: 12px; background: #f9fafb; border-radius: 5px;">
            <p><strong style="color: #10b981;">Diagnosis:</strong> {{ $claim->diagnosis_code }} - {{ $claim->diagnosis_description }}</p>
        </div>
    @endif

    @if($claim->treatment_details)
        <div style="margin-top: 10px; padding: 12px; background: #f9fafb; border-radius: 5px;">
            <p><strong style="color: #10b981;">Treatment Details:</strong></p>
            <p>{{ $claim->treatment_details }}</p>
        </div>
    @endif

    @if($claim->documents && count($claim->documents) > 0)
        <div class="docs-list">
            <h4>Supporting Documents</h4>
            <ul>
                @foreach($claim->documents as $doc)
                    <li>{{ is_array($doc) ? ($doc['name'] ?? 'Document') : $doc }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="footer">
        <p>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }} | {{ \App\Models\SystemSetting::get('hospital_address', '') }} | {{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>
</html>



