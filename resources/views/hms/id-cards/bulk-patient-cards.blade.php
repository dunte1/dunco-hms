<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bulk Patient ID Cards</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; background: white; }
        .page { width: 100%; padding: 10px; }
        .id-card {
            width: 370px;
            height: 240px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
            display: inline-block;
            margin: 5px;
            vertical-align: top;
            page-break-inside: avoid;
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
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        .hospital-name {
            color: white;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .card-type {
            background: rgba(255,255,255,0.3);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }
        .card-body {
            background: white;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            gap: 10px;
            position: relative;
            z-index: 1;
        }
        .photo-section {
            width: 60px;
            height: 75px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            flex-shrink: 0;
            overflow: hidden;
        }
        .info-section { flex: 1; }
        .patient-name { font-size: 14px; font-weight: bold; color: #333; margin-bottom: 4px; }
        .patient-id { font-size: 10px; color: #666; margin-bottom: 6px; font-family: 'Courier New', monospace; }
        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; margin-top: 6px; }
        .detail-item { font-size: 9px; color: #666; }
        .detail-label { font-weight: bold; color: #999; display: block; margin-bottom: 1px; }
        .detail-value { color: #333; }
        .barcode-section { margin-top: 4px; padding: 3px; background: #f5f5f5; border-radius: 3px; text-align: center; }
        .barcode-section img { max-width: 100%; height: 22px; }
        .qr-section {
            position: absolute;
            top: 10px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            overflow: hidden;
        }
        .qr-section img { width: 36px; height: 36px; }
        .footer {
            margin-top: 8px;
            text-align: center;
            color: rgba(255,255,255,0.8);
            font-size: 8px;
            position: relative;
            z-index: 1;
        }
        .watermark {
            position: absolute;
            bottom: 8px;
            right: 12px;
            font-size: 7px;
            color: rgba(255,255,255,0.5);
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="page">
        @foreach($patients as $patient)
            <div class="id-card">
                <div class="card-header">
                    <div class="hospital-name">{{ strtoupper(\App\Models\SystemSetting::get('hospital_name', config('app.name'))) }}</div>
                    <div class="card-type">PATIENT</div>
                </div>
                
                <div class="qr-section">
                    @if(!empty($patient->base64Qr))
                        <img src="data:image/png;base64,{{ $patient->base64Qr }}" alt="QR">
                    @else
                        ▣
                    @endif
                </div>
                
                <div class="card-body">
                    <div class="photo-section" style="{{ !empty($patient->base64Photo) ? 'background: white; padding: 3px;' : '' }}">
                        @if(!empty($patient->base64Photo))
                            <img src="data:image/jpeg;base64,{{ $patient->base64Photo }}" alt="Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 3px;">
                        @else
                            {{ strtoupper(substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1)) }}
                        @endif
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
                            @if(!empty($patient->base64Barcode))
                                <img src="data:image/png;base64,{{ $patient->base64Barcode }}" alt="Barcode">
                            @else
                                <div style="font-family: 'Courier New', monospace; font-size: 10px; letter-spacing: 1px;">▍ ▍▍ ▍▍▍ ▍ ▍▍▍ ▍▍</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="footer">
                    <div>This card is property of {{ strtoupper(\App\Models\SystemSetting::get('hospital_name', config('app.name'))) }}</div>
                </div>
                
                <div class="watermark">{{ date('Y') }}</div>
            </div>
        @endforeach
    </div>
</body>
</html>
