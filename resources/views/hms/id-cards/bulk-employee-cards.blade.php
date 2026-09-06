<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bulk Employee ID Cards</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; background: white; }
        .page { width: 100%; padding: 10px; }
        .id-card {
            width: 370px;
            height: 240px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        .employee-name { font-size: 14px; font-weight: bold; color: #333; margin-bottom: 3px; }
        .employee-id { font-size: 10px; color: #666; margin-bottom: 4px; font-family: 'Courier New', monospace; }
        .department { font-size: 10px; color: #f5576c; font-weight: bold; margin-bottom: 3px; }
        .position { font-size: 9px; color: #999; margin-bottom: 4px; }
        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 3px; margin-top: 4px; }
        .detail-item { font-size: 8px; color: #666; }
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
        .badge-section {
            position: absolute;
            top: 8px;
            left: 20px;
            background: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 8px;
            color: white;
            font-weight: bold;
            z-index: 1;
        }
    </style>
</head>
<body>
    @php
        $themeSettings = \App\Models\[ "primary_color" => \App\Models\SystemSetting::get("primary_color", "#10b981"), "hospital_logo" => \App\Models\SystemSetting::get("hospital_logo", ""), "hospital_name" => \App\Models\SystemSetting::get("hospital_name", config("app.name", "DuncoHMS")), "hospital_address" => \App\Models\SystemSetting::get("hospital_address", ""), "hospital_phone" => \AppModels\SystemSetting::get("hospital_phone", ""), "hospital_email" => \App\Models\SystemSetting::get("hospital_email", "") ];
    @endphp
    <div class="page">
        @foreach($employees as $employee)
            <div class="id-card">
                <div class="badge-section">STAFF</div>
                <div class="card-header">
                    @if(isset($themeSettings) && !empty($themeSettings['hospital_logo']))
                        <div class="hospital-name"><img src="data:image/png;base64,{{ $themeSettings['hospital_logo'] }}" style="height: 20px; margin-right: 5px;"></div>
                    @else
                        <div class="hospital-name">{{ strtoupper(\App\Models\SystemSetting::get('hospital_name', config('app.name'))) }}</div>
                    @endif
                    <div class="card-type">EMPLOYEE</div>
                </div>
                
                <div class="qr-section">
                    @if(!empty($employee->base64Qr))
                        <img src="data:image/png;base64,{{ $employee->base64Qr }}" alt="QR">
                    @else
                        ▣
                    @endif
                </div>
                
                <div class="card-body">
                    <div class="photo-section" style="{{ !empty($employee->base64Photo) ? 'background: white; padding: 3px;' : '' }}">
                        @if(!empty($employee->base64Photo))
                            <img src="data:image/jpeg;base64,{{ $employee->base64Photo }}" alt="Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 3px;">
                        @else
                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                        @endif
                    </div>
                    
                    <div class="info-section">
                        <div class="employee-name">{{ $employee->full_name }}</div>
                        <div class="employee-id">ID: {{ $employee->employee_id }}</div>
                        <div class="department">{{ $employee->department->name ?? 'N/A' }}</div>
                        <div class="position">{{ $employee->position }}</div>
                        <div class="details">
                            <div class="detail-item">
                                <span class="detail-label">Gender</span>
                                <span class="detail-value">{{ ucfirst($employee->gender ?? 'N/A') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Hired</span>
                                <span class="detail-value">{{ $employee->hire_date ? date('M Y', strtotime($employee->hire_date)) : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="barcode-section">
                            @if(!empty($employee->base64Barcode))
                                <img src="data:image/png;base64,{{ $employee->base64Barcode }}" alt="Barcode">
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

