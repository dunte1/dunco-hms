<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - DuncoHMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .verify-card { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 480px; margin: auto; overflow: hidden; }
        .verify-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; text-align: center; }
        .verify-header i { font-size: 3rem; margin-bottom: 1rem; }
        .otp-input { width: 56px; height: 64px; text-align: center; font-size: 1.8rem; font-weight: bold; border: 2px solid #dee2e6; border-radius: 12px; transition: all 0.3s; }
        .otp-input:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.25); outline: none; }
        .btn-verify { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px; font-weight: 600; width: 100%; border-radius: 8px; color: white; transition: transform 0.2s; }
        .btn-verify:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(102,126,234,0.4); color: white; }
        .btn-resend { color: #667eea; text-decoration: none; font-weight: 500; cursor: pointer; }
        .btn-resend:hover { text-decoration: underline; }
        .timer { color: #6c757d; font-size: 0.9rem; }
        .alert { border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="verify-card">
            <div class="verify-header">
                <i class="fas fa-shield-alt"></i>
                <h3>Verify Your Email</h3>
                <p class="mb-0 opacity-75">Enter the 6-digit code sent to your email</p>
            </div>
            
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('status'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(!$user || $user->email_verified_at)
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Email Already Verified</h5>
                        <p class="text-muted">You can log in now.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
                    </div>
                @else
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-envelope-open-text text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <p class="text-muted mt-3 mb-1">We sent a 6-digit code to</p>
                        <p class="fw-bold mb-0">{{ $email }}</p>
                    </div>

                    <!-- OTP Form -->
                    <form action="{{ route('otp.verify') }}" method="POST" id="otpForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        
                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <input type="text" name="code" class="otp-input form-control" maxlength="6" 
                                   pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code"
                                   id="otpInput" required autofocus
                                   placeholder="000000">
                        </div>

                        <button type="submit" class="btn btn-verify mb-3">
                            <i class="fas fa-check me-2"></i>Verify Code
                        </button>
                    </form>

                    <div class="text-center">
                        <p class="timer mb-2" id="timer">Code expires in 10:00</p>
                        <form action="{{ route('otp.resend') }}" method="POST" class="d-inline" id="resendForm">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <button type="submit" class="btn-resend" id="resendBtn">
                                <i class="fas fa-redo me-1"></i>Resend Code
                            </button>
                        </form>
                    </div>
                @endif

                <hr class="my-4">
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-submit when 6 digits entered
        const otpInput = document.getElementById('otpInput');
        if (otpInput) {
            otpInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 6) {
                    document.getElementById('otpForm').submit();
                }
            });
        }

        // Countdown timer
        let seconds = 600; // 10 minutes
        const timerEl = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');
        
        if (timerEl) {
            const interval = setInterval(function() {
                seconds--;
                const mins = Math.floor(seconds / 60);
                const secs = seconds % 60;
                timerEl.textContent = `Code expires in ${mins}:${secs.toString().padStart(2, '0')}`;
                
                if (seconds <= 0) {
                    clearInterval(interval);
                    timerEl.textContent = 'Code has expired';
                    timerEl.classList.add('text-danger');
                    if (resendBtn) resendBtn.classList.remove('disabled');
                }
            }, 1000);
        }
    </script>
</body>
</html>
