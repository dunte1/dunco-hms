<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Hospital Summary - {{ $date ?? now()->format('M d, Y') }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    @php
        $hospitalName = \App\Models\SystemSetting::get('hospital_name', config('app.name', 'DuncoHMS'));
        $hospitalLogo = \App\Models\SystemSetting::get('hospital_logo', '');
        $hospitalAddress = \App\Models\SystemSetting::get('hospital_address', '');
        $hospitalPhone = \App\Models\SystemSetting::get('hospital_phone', '');
        $currencySymbol = \App\Models\SystemSetting::get('currency_symbol', 'KES');
    @endphp

    <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        @if($hospitalLogo)
            <img src="{{ $hospitalLogo }}" alt="Logo" style="max-height: 40px; margin-bottom: 10px;">
        @endif
        <h1 style="color: white; margin: 0;">Daily Hospital Summary</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 5px 0 0 0;">{{ $hospitalName }}</p>
    </div>

    <div style="background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $recipientName ?? 'Admin' }},</p>

        <p>Here is your daily hospital summary for <strong>{{ $date ?? now()->format('l, F d, Y') }}</strong>.</p>

        @if(isset($summary))
        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #059669;">
            <h3 style="margin-top: 0; color: #059669;">Appointments</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr><td style="padding: 5px 0;">Total Appointments</td><td style="text-align: right; font-weight: bold;">{{ $summary['appointments']['total'] ?? 0 }}</td></tr>
                <tr><td style="padding: 5px 0;">Completed</td><td style="text-align: right; font-weight: bold; color: #059669;">{{ $summary['appointments']['completed'] ?? 0 }}</td></tr>
                <tr><td style="padding: 5px 0;">Pending</td><td style="text-align: right; font-weight: bold; color: #f59e0b;">{{ $summary['appointments']['pending'] ?? 0 }}</td></tr>
                <tr><td style="padding: 5px 0;">Cancelled</td><td style="text-align: right; font-weight: bold; color: #ef4444;">{{ $summary['appointments']['cancelled'] ?? 0 }}</td></tr>
            </table>
        </div>

        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #3b82f6;">
            <h3 style="margin-top: 0; color: #3b82f6;">Patient Activity</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr><td style="padding: 5px 0;">New Registrations</td><td style="text-align: right; font-weight: bold;">{{ $summary['patients']['new_registrations'] ?? 0 }}</td></tr>
                <tr><td style="padding: 5px 0;">OPD Visits</td><td style="text-align: right; font-weight: bold;">{{ $summary['patients']['opd_visits'] ?? 0 }}</td></tr>
                <tr><td style="padding: 5px 0;">IPD Admissions</td><td style="text-align: right; font-weight: bold;">{{ $summary['patients']['ipd_admissions'] ?? 0 }}</td></tr>
            </table>
        </div>

        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #10b981;">
            <h3 style="margin-top: 0; color: #10b981;">Financial Summary</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr><td style="padding: 5px 0;">Revenue Collected</td><td style="text-align: right; font-weight: bold; color: #10b981;">{{ $currencySymbol }} {{ number_format($summary['financial']['total_revenue'] ?? 0, 2) }}</td></tr>
                <tr><td style="padding: 5px 0;">Invoices Generated</td><td style="text-align: right; font-weight: bold;">{{ $summary['financial']['invoices_generated'] ?? 0 }}</td></tr>
                <tr><td style="padding: 5px 0;">Payments Received</td><td style="text-align: right; font-weight: bold;">{{ $summary['financial']['payments_received'] ?? 0 }}</td></tr>
                <tr><td style="padding: 5px 0;">Pending Amount</td><td style="text-align: right; font-weight: bold; color: #f59e0b;">{{ $currencySymbol }} {{ number_format($summary['financial']['pending_amount'] ?? 0, 2) }}</td></tr>
            </table>
        </div>

        @if(isset($summary['diagnostics']))
        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #8b5cf6;">
            <h3 style="margin-top: 0; color: #8b5cf6;">Diagnostics</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr><td style="padding: 5px 0;">Lab Tests</td><td style="text-align: right; font-weight: bold;">{{ $summary['diagnostics']['lab_tests'] ?? 0 }}</td></tr>
                <tr><td style="padding: 5px 0;">Radiology Tests</td><td style="text-align: right; font-weight: bold;">{{ $summary['diagnostics']['radiology_tests'] ?? 0 }}</td></tr>
                <tr><td style="padding: 5px 0;">Completed Tests</td><td style="text-align: right; font-weight: bold; color: #059669;">{{ $summary['diagnostics']['completed_tests'] ?? 0 }}</td></tr>
            </table>
        </div>
        @endif
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('dashboard') }}" style="background: #059669; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Dashboard</a>
        </div>

        <p>Best regards,<br>
        <strong>{{ $hospitalName }} System</strong></p>
    </div>

    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p><strong>{{ $hospitalName }}</strong></p>
        <p>{{ $hospitalAddress }}</p>
        <p>{{ $hospitalPhone }}</p>
        <p>&copy; {{ date('Y') }} {{ $hospitalName }}. All rights reserved.</p>
    </div>
</body>
</html>
