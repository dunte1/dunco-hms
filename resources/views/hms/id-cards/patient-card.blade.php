<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Patient ID Card - {{ $patient->patient_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #f5f5f5;
        }
        .id-card {
            width: 370px;
            height: 240px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }
        .id-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        .hospital-name {
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .card-type {
            background: rgba(255,255,255,0.3);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .card-body {
            background: white;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            gap: 15px;
            position: relative;
            z-index: 1;
        }
        .photo-section {
            width: 80px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .info-section {
            flex: 1;
        }
        .patient-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        .patient-id {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            font-family: 'Courier New', monospace;
        }
        .details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 10px;
        }
        .detail-item {
            font-size: 11px;
            color: #666;
        }
        .detail-label {
            font-weight: bold;
            color: #999;
            display: block;
            margin-bottom: 2px;
        }
        .detail-value {
            color: #333;
        }
        .barcode-section {
            margin-top: 8px;
            padding: 5px;
            background: #f5f5f5;
            border-radius: 5px;
            text-align: center;
        }
        .barcode {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            letter-spacing: 1px;
            font-weight: bold;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            color: rgba(255,255,255,0.8);
            font-size: 10px;
            position: relative;
            z-index: 1;
        }
        .qr-placeholder {
            position: absolute;
            top: 15px;
            right: 25px;
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            z-index: 1;
        }
        .watermark {
            position: absolute;
            bottom: 10px;
            right: 15px;
            font-size: 8px;
            color: rgba(255,255,255,0.5);
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="card-header">
            <div class="hospital-name">DUNCOHMS</div>
            <div class="card-type">PATIENT</div>
        </div>
        
        <div class="qr-placeholder">▣</div>
        
        <div class="card-body">
            <div class="photo-section">
                {{ strtoupper(substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1)) }}
            </div>
            
            <div class="info-section">
                <div class="patient-name">{{ $patient->full_name }}</div>
                <div class="patient-id">ID: {{ $patient->patient_no }}</div>
                
                <div class="details">
                    <div class="detail-item">
                        <span class="detail-label">DOB</span>
                        <span class="detail-value">{{ $patient->dob ? date('M d, Y', strtotime($patient->dob)) : 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Gender</span>
                        <span class="detail-value">{{ ucfirst($patient->gender ?? 'N/A') }}</span>
                    </div>
                </div>
                
                <div class="barcode-section">
                    <div class="barcode">▍ ▍▍ ▍▍▍ ▍ ▍▍▍ ▍▍</div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div>This card is property of DUNCOHMS HOSPITAL</div>
        </div>
        
        <div class="watermark">{{ date('Y') }}</div>
    </div>
</body>
</html>
