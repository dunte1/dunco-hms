{{-- --}}
@props(['title' => 'Document', 'subtitle' => '', 'documentNumber' => '', 'showFooter' => true])

@php
    $hospitalName = \App\Models\SystemSetting::get('hospital_name', config('app.name', 'Dunco HMS'));
    $hospitalAddress = \App\Models\SystemSetting::get('hospital_address', '');
    $hospitalPhone = \App\Models\SystemSetting::get('hospital_phone', '');
    $hospitalEmail = \App\Models\SystemSetting::get('hospital_email', '');
    $hospitalWebsite = \App\Models\SystemSetting::get('hospital_website', '');
    $primaryColor = \App\Models\SystemSetting::get('primary_color', '#000075');
    $logo = \App\Models\SystemSetting::get('hospital_logo', '');
    $logoSrc = '';
    if ($logo) {
        if (str_starts_with($logo, 'http') || str_starts_with($logo, 'data:')) {
            $logoSrc = $logo;
        } elseif (str_starts_with($logo, '/storage/')) {
            $logoSrc = asset($logo);
        } else {
            $logoSrc = asset('storage/' . $logo);
        }
    }
    $footerText = \App\Models\SystemSetting::get('document_footer', '');
    $currencySymbol = \App\Models\SystemSetting::get('currency_symbol', 'KES');
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }} - {{ $hospitalName }}</title>
    <style>
        @page { size: A4; margin: 15mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        
        /* Header */
        .doc-header { text-align: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 3px solid {{ $primaryColor }}; }
        .doc-header .logo { height: 55px; margin-bottom: 8px; }
        .doc-header h1 { color: {{ $primaryColor }}; font-size: 20px; margin-bottom: 4px; }
        .doc-header p { color: #555; font-size: 11px; margin: 2px 0; }
        .doc-header .contact { color: #777; font-size: 10px; }
        .doc-header .doc-title { background: {{ $primaryColor }}; color: white; padding: 6px 20px; border-radius: 4px; display: inline-block; margin-top: 8px; font-size: 13px; font-weight: bold; }
        .doc-header .doc-meta { color: #999; font-size: 9px; margin-top: 4px; }
        
        /* Footer - fixed to bottom */
        .doc-footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 8px 15mm; border-top: 2px solid {{ $primaryColor }}; background: #f9fafb; text-align: center; font-size: 9px; color: #666; }
        .doc-footer .footer-brand { font-weight: bold; color: {{ $primaryColor }}; }
        .doc-footer .footer-powered { color: #999; margin-top: 2px; }
        .doc-footer .footer-powered a { color: {{ $primaryColor }}; text-decoration: none; }
        
        /* Content area - with bottom padding for footer */
        .doc-content { padding-bottom: 60px; }
        
        /* Tables */
        table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        th { background: #f3f4f6; padding: 8px 6px; text-align: left; font-weight: bold; border-bottom: 2px solid {{ $primaryColor }}; font-size: 10px; color: #333; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) { background: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Summary boxes */
        .summary { display: table; width: 100%; margin-bottom: 15px; }
        .summary-box { display: table-cell; text-align: center; padding: 10px; background: #f3f4f6; border-radius: 5px; border-left: 3px solid {{ $primaryColor }}; }
        .summary-box .amount { font-size: 16px; font-weight: bold; color: {{ $primaryColor }}; }
        .summary-box .label { font-size: 9px; color: #666; text-transform: uppercase; }
        
        /* Section titles */
        .section-title { color: {{ $primaryColor }}; font-size: 13px; font-weight: bold; margin: 15px 0 8px 0; padding-bottom: 4px; border-bottom: 1px solid #e5e7eb; }
        
        /* Info rows */
        .info-table td { border-bottom: 1px solid #eee; }
        .info-table td:first-child { font-weight: bold; width: 30%; color: #555; }
        
        /* Print */
        @media print {
            body { padding: 0; margin: 0; }
            .no-print { display: none !important; }
            table { page-break-inside: avoid; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="doc-header">
        @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="{{ $hospitalName }}" class="logo">
        @endif
        <h1>{{ $hospitalName }}</h1>
        <p>{{ $hospitalAddress }}</p>
        <p class="contact">Tel: {{ $hospitalPhone }} | Email: {{ $hospitalEmail }}@if($hospitalWebsite) | Web: {{ $hospitalWebsite }}@endif</p>
        <div class="doc-title">{{ $title }}</div>
        @if($subtitle)
            <div class="doc-meta">{{ $subtitle }}</div>
        @endif
        @if($documentNumber)
            <div class="doc-meta">Document #: {{ $documentNumber }}</div>
        @endif
        <div class="doc-meta">Generated: {{ now()->format('M d, Y h:i A') }}</div>
    </div>

    {{-- Content --}}
    <div class="doc-content">
        {{ $slot }}
    </div>

    {{-- Footer --}}
    @if($showFooter)
    <div class="doc-footer">
        <div class="footer-brand">{{ $hospitalName }}</div>
        <div>{{ $hospitalAddress }} | {{ $hospitalPhone }} | {{ $hospitalEmail }}</div>
        @if($footerText)
            <div>{{ $footerText }}</div>
        @endif
        <div class="footer-powered">&copy; {{ date('Y') }} {{ $hospitalName }}. Powered by <a href="https://duncowebsolutions.co.ke">Dunco Web Solutions</a></div>
    </div>
    @endif
</body>
</html>
