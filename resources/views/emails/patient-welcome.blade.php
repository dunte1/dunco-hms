<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Welcome to {{ config('app.name') }}</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $patient->first_name }},</p>
        
        <p>Thank you for registering with {{ config('app.name') }}. We're pleased to have you as our patient.</p>
        
        <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #667eea;">
            <h3 style="margin-top: 0; color: #667eea;">Your Patient Information</h3>
            <p><strong>Patient ID:</strong> {{ $patientNo }}</p>
            <p><strong>Name:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</p>
            @if($patient->date_of_birth)
            <p><strong>Date of Birth:</strong> {{ $patient->date_of_birth->format('M d, Y') }}</p>
            @endif
        </div>
        
        <p>With your patient portal account, you can:</p>
        <ul>
            <li>View and manage your appointments</li>
            <li>Access your medical records and lab results</li>
            <li>View your prescriptions</li>
            <li>Check your billing and payment history</li>
            <li>Update your personal information</li>
        </ul>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('patient-portal.login') }}" style="background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Access Patient Portal</a>
        </div>
        
        <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
        
        <p>Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>

