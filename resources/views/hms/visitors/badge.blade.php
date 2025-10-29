<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Badge - {{ $visitor->badge_number }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        .badge-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .badge-header {
            background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
        .badge-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .badge-header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .badge-content {
            text-align: center;
            padding: 20px 0;
        }
        .badge-number {
            font-size: 48px;
            font-weight: bold;
            color: #9333ea;
            margin: 20px 0;
            letter-spacing: 2px;
        }
        .visitor-info {
            text-align: left;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #e5e7eb;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-label {
            font-weight: bold;
            color: #6b7280;
            font-size: 12px;
        }
        .info-value {
            color: #1f2937;
            font-size: 14px;
            text-align: right;
        }
        .qr-code-placeholder {
            width: 120px;
            height: 120px;
            margin: 20px auto;
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 12px;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
        .btn-print {
            background: #9333ea;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-print:hover {
            background: #7c3aed;
        }
    </style>
</head>
<body>
    <div class="badge-container">
        <div class="badge-header">
            <h1>VISITOR</h1>
            <p>Hospital Management System</p>
        </div>

        <div class="badge-content">
            <div class="badge-number">{{ $visitor->badge_number }}</div>
            
            <div class="visitor-info">
                <div class="info-row">
                    <span class="info-label">Visitor Name:</span>
                    <span class="info-value">{{ $visitor->visitor_name }}</span>
                </div>
                
                @if($visitor->visitor_phone)
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $visitor->visitor_phone }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="info-label">Type:</span>
                    <span class="info-value">{{ ucfirst($visitor->visitor_type) }}</span>
                </div>
                
                @if($visitor->patient_name)
                <div class="info-row">
                    <span class="info-label">Visiting:</span>
                    <span class="info-value">{{ $visitor->patient_name }}</span>
                </div>
                @endif
                
                @if($visitor->department)
                <div class="info-row">
                    <span class="info-label">Department:</span>
                    <span class="info-value">{{ $visitor->department }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="info-label">Check In:</span>
                    <span class="info-value">{{ $visitor->check_in_time->format('M d, Y h:i A') }}</span>
                </div>
                
                @if($visitor->check_out_time)
                <div class="info-row">
                    <span class="info-label">Check Out:</span>
                    <span class="info-value">{{ $visitor->check_out_time->format('M d, Y h:i A') }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <strong>{{ ucfirst(str_replace('_', ' ', $visitor->status)) }}</strong>
                    </span>
                </div>
            </div>

            <!-- QR Code Placeholder -->
            <div class="qr-code-placeholder">
                QR Code<br>{{ $visitor->badge_number }}
            </div>
        </div>

        <div class="print-button no-print">
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Print Badge
            </button>
            <a href="{{ route('hms.visitors.index') }}" style="display: inline-block; margin-left: 10px; padding: 12px 24px; background: #6b7280; color: white; text-decoration: none; border-radius: 6px;">
                Back
            </a>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>

