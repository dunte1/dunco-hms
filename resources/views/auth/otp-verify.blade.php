<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - {{ \App\Models\SystemSetting::get('hospital_name', 'Dunco HMS') }}</title>
    @php
        $primaryColor = \App\Models\SystemSetting::get('primary_color', '#000075');
        $secondaryColor = \App\Models\SystemSetting::get('secondary_color', '#00001A');
        $hospitalName = \App\Models\SystemSetting::get('hospital_name', 'Dunco HMS');
    @endphp
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; background: #F7F9FA; }
        
        .container { display: flex; min-height: 100vh; }
        
        .left-panel {
            display: none;
            width: 50%;
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
            align-items: center;
            justify-content: center;
            color: white;
        }
        @media (min-width: 1024px) { .left-panel { display: flex; } }
        
        .left-content { max-width: 420px; text-align: center; padding: 2rem; }
        .left-icon { width: 64px; height: 64px; background: rgba(255,255,255,0.15); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; backdrop-filter: blur(4px); }
        .left-icon i { font-size: 28px; color: white; }
        .left-title { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        .left-subtitle { font-size: 16px; opacity: 0.7; margin-bottom: 2.5rem; }
        .left-features { text-align: left; }
        .left-feature { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; opacity: 0.85; font-size: 15px; }
        .left-feature i { width: 20px; color: white; }
        
        .right-panel {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 1.5rem;
        }
        @media (min-width: 1024px) { .right-panel { width: 50%; } }
        
        .form-container { width: 100%; max-width: 420px; }
        
        .mobile-header { text-align: center; margin-bottom: 2rem; }
        @media (min-width: 1024px) { .mobile-header { display: none; } }
        .mobile-icon { width: 48px; height: 48px; background: linear-gradient(135deg, {{ $primaryColor }}, {{ $secondaryColor }}); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
        .mobile-icon i { color: white; font-size: 22px; }
        .mobile-name { font-size: 18px; font-weight: 700; color: #111827; }
        
        .page-title { font-size: 24px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .page-subtitle { font-size: 14px; color: #6B7280; margin-bottom: 2rem; }
        
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; }
        .alert-info { background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF; }
        .alert-danger { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; }
        
        .icon-circle { width: 80px; height: 80px; border-radius: 50%; background: {{ $primaryColor }}15; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
        .icon-circle i { font-size: 32px; color: {{ $primaryColor }}; }
        
        .email-label { font-size: 14px; color: #6B7280; margin-bottom: 4px; }
        .email-value { font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 24px; }
        
        .otp-input {
            width: 100%;
            max-width: 280px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            border: 2px solid #E5ECEB;
            border-radius: 12px;
            transition: all 0.3s;
            background: white;
            letter-spacing: 8px;
            margin: 0 auto 24px;
            display: block;
        }
        .otp-input:focus { border-color: {{ $primaryColor }}; box-shadow: 0 0 0 3px {{ $primaryColor }}25; outline: none; }
        
        .btn-verify {
            width: 100%;
            height: 48px;
            background: {{ $primaryColor }};
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 16px;
        }
        .btn-verify:hover { background: {{ $secondaryColor }}; transform: translateY(-1px); box-shadow: 0 4px 12px {{ $primaryColor }}40; }
        
        .timer { font-size: 13px; color: #6B7280; margin-bottom: 8px; }
        .btn-resend { background: none; border: none; color: {{ $primaryColor }}; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; }
        .btn-resend:hover { text-decoration: underline; }
        
        .back-link { display: block; text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid #E5ECEB; }
        .back-link a { color: {{ $primaryColor }}; font-size: 14px; font-weight: 600; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
        
        .footer-text { text-align: center; margin-top: 24px; font-size: 12px; color: #9CA3AF; }
        .footer-text a { color: {{ $primaryColor }}; text-decoration: none; }
        
        .success-icon { font-size: 48px; color: #16A34A; margin-bottom: 16px; }
        .success-title { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .success-text { font-size: 14px; color: #6B7280; margin-bottom: 20px; }
        .btn-success { display: inline-block; background: {{ $primaryColor }}; color: white; padding: 12px 30px; border-radius: 8px; font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        {{-- Left Panel --}}
        <div class="left-panel">
            <div class="left-content">
                <div class="left-icon">
                    <i class="fa fa-shield-alt"></i>
                </div>
                <h1 class="left-title">{{ $hospitalName }}</h1>
                <p class="left-subtitle">Secure Email Verification</p>
                <div class="left-features">
                    <div class="left-feature"><i class="fa fa-check-circle"></i><span>Quick and secure verification</span></div>
                    <div class="left-feature"><i class="fa fa-check-circle"></i><span>Protect your account</span></div>
                    <div class="left-feature"><i class="fa fa-check-circle"></i><span>Access all system features</span></div>
                </div>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="right-panel">
            <div class="form-container">
                {{-- Mobile Header --}}
                <div class="mobile-header">
                    <div class="mobile-icon"><i class="fa fa-shield-alt"></i></div>
                    <div class="mobile-name">{{ $hospitalName }}</div>
                </div>

                <h1 class="page-title">Verify Your Email</h1>
                <p class="page-subtitle">Enter the 6-digit code sent to your email</p>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('status'))
                    <div class="alert alert-info">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                @if(!$user || $user->email_verified_at)
                    <div style="text-align: center; padding: 2rem 0;">
                        <div class="success-icon"><i class="fa fa-check-circle"></i></div>
                        <div class="success-title">Email Already Verified</div>
                        <div class="success-text">You can log in now.</div>
                        <a href="{{ route('login') }}" class="btn-success">Go to Login</a>
                    </div>
                @else
                    <div style="text-align: center; margin-bottom: 24px;">
                        <div class="icon-circle">
                            <i class="fa fa-envelope-open-text"></i>
                        </div>
                        <div class="email-label">We sent a 6-digit code to</div>
                        <div class="email-value">{{ $email }}</div>
                    </div>

                    <form action="{{ route('otp.verify') }}" method="POST" id="otpForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="text" name="code" class="otp-input" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code" id="otpInput" required autofocus placeholder="000000">
                        <button type="submit" class="btn-verify">
                            <i class="fa fa-check me-2"></i>Verify Code
                        </button>
                    </form>

                    <div style="text-align: center;">
                        <div class="timer" id="timer">Code expires in 10:00</div>
                        <form action="{{ route('otp.resend') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <button type="submit" class="btn-resend" id="resendBtn">
                                <i class="fa fa-redo me-1"></i>Resend Code
                            </button>
                        </form>
                    </div>
                @endif

                <div class="back-link">
                    <a href="{{ route('login') }}">&larr; Back to Login</a>
                </div>

                <div class="footer-text">
                    &copy; {{ date('Y') }} {{ $hospitalName }}. Powered by <a href="https://duncowebsolutions.co.ke">Dunco Web Solutions</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const otpInput = document.getElementById('otpInput');
        if (otpInput) {
            otpInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 6) document.getElementById('otpForm').submit();
            });
        }
        let seconds = 600;
        const timerEl = document.getElementById('timer');
        if (timerEl) {
            const interval = setInterval(function() {
                seconds--;
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                timerEl.textContent = 'Code expires in ' + m + ':' + s.toString().padStart(2, '0');
                if (seconds <= 0) { clearInterval(interval); timerEl.textContent = 'Code expired'; timerEl.style.color = '#DC2626'; }
            }, 1000);
        }
    </script>
</body>
</html>
