<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Training Certificate</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 40px;
        }
        .certificate {
            border: 10px solid #1a237e;
            padding: 60px;
            text-align: center;
            background: white;
        }
        .header {
            font-size: 48px;
            font-weight: bold;
            color: #1a237e;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 5px;
        }
        .subheader {
            font-size: 24px;
            color: #666;
            margin-bottom: 40px;
        }
        .certificate-text {
            font-size: 28px;
            margin: 40px 0;
            line-height: 1.8;
        }
        .employee-name {
            font-size: 36px;
            font-weight: bold;
            color: #1a237e;
            margin: 20px 0;
            text-decoration: underline;
        }
        .program-details {
            font-size: 20px;
            margin: 30px 0;
            line-height: 1.6;
        }
        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            width: 200px;
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 80px;
        }
        .date {
            font-size: 18px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">Certificate of Completion</div>
        <div class="subheader">This is to certify that</div>
        
        <div class="employee-name">{{ $employee->full_name }}</div>
        
        <div class="certificate-text">
            has successfully completed the training program
        </div>
        
        <div class="program-details">
            <strong>{{ $program->title }}</strong><br>
            @if($program->category)
                Category: {{ $program->category }}<br>
            @endif
            Duration: {{ $program->duration_hours }} hours<br>
            @if($program->location)
                Location: {{ $program->location }}<br>
            @endif
            @if($enrollment->attendance_hours)
                Hours Attended: {{ $enrollment->attendance_hours }} hours<br>
            @endif
        </div>
        
        <div class="date">
            Completed on: {{ $completion_date->format('F d, Y') }}
        </div>
        
        <div class="footer">
            <div class="signature">
                <div style="height: 60px;"></div>
                <div>Training Coordinator</div>
            </div>
            <div class="signature">
                <div style="height: 60px;"></div>
                <div>HR Manager</div>
            </div>
        </div>
        
        <div style="margin-top: 40px; font-size: 14px; color: #666;">
            Certificate ID: TR-{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}
        </div>
    </div>
</body>
</html>

