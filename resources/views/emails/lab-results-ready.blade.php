<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Results Ready</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        @php
            $hospitalLogo = \App\Models\SystemSetting::get('hospital_logo', '');
        @endphp
        @if($hospitalLogo && file_exists(storage_path('app/public/' . $hospitalLogo)))
            @php
                $logoPath = storage_path('app/public/' . $hospitalLogo);
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoMime = mime_content_type($logoPath);
            @endphp
            <img src="data:{{ $logoMime }};base64,{{ $logoData }}" alt="Logo" style="max-height: 40px; margin-bottom: 10px;">
        @endif
        <h1 style="color: white; margin: 0;">{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
        <p style="color: rgba(255,255,255,0.8); margin: 5px 0 0 0;">Lab Results Ready</p>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $patient->first_name }},</p>
        
        <p>Your laboratory test results are now ready for review:</p>
        
        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #10b981;">
            <h3 style="margin-top: 0; color: #10b981;">Lab Request Details</h3>
            <p><strong>Request Number:</strong> {{ $labRequest->request_number }}</p>
            <p><strong>Patient:</strong> {{ $labRequest->patient->full_name ?? 'N/A' }}</p>
            <p><strong>Request Date:</strong> {{ $labRequest->request_date->format('M d, Y') }}</p>
            <p><strong>Status:</strong> Completed</p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('hms.lab.requests.show', $labRequest->id) }}" style="background: #10b981; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Results</a>
        </div>
        
        <p>For any questions about your results, please consult with your physician.</p>
        
        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p><strong>{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</strong></p>
        <p>{{ \App\Models\SystemSetting::get('hospital_address', '') }}</p>
        <p>{{ \App\Models\SystemSetting::get('hospital_phone', '') }}</p>
        <p>&copy; {{ date('Y') }} {{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}. All rights reserved.</p>
    </div>
</body>
</html>

