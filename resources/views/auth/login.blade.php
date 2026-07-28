<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'DuncoHMS') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @php
        $primaryColor = \App\Models\SystemSetting::get('primary_color', '#6366f1');
        $secondaryColor = \App\Models\SystemSetting::get('secondary_color', '#8b5cf6');
        
        // Convert hex to RGB for rgba shadows
        function hexToRgb($hex) {
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            return "$r, $g, $b";
        }
        
        $primaryRgb = hexToRgb($primaryColor);
    @endphp
    
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
            --primary-shadow: rgba({{ $primaryRgb }}, 0.35);
            --primary-shadow-hover: rgba({{ $primaryRgb }}, 0.45);
            --primary-shadow-light: rgba({{ $primaryRgb }}, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }} 25%, {{ $primaryColor }} 50%, {{ $secondaryColor }} 75%, {{ $secondaryColor }} 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Animated Background Elements */
        .bg-decoration {
            position: fixed;
            border-radius: 50%;
            opacity: 0.1;
            pointer-events: none;
            z-index: 1;
        }
        
        .bg-circle-1 {
            width: 400px;
            height: 400px;
            background: white;
            top: -100px;
            left: -100px;
            animation: float 20s ease-in-out infinite;
        }
        
        .bg-circle-2 {
            width: 300px;
            height: 300px;
            background: white;
            bottom: -80px;
            right: -80px;
            animation: float 15s ease-in-out infinite reverse;
        }
        
        .bg-circle-3 {
            width: 200px;
            height: 200px;
            background: white;
            top: 40%;
            right: 10%;
            animation: float 18s ease-in-out infinite;
            animation-delay: -5s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.1); }
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            position: relative;
            z-index: 10;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .logo-container img {
            max-width: 120px;
            max-height: 80px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(255, 255, 255, 0.2));
        }
        
        .logo-container .logo-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            box-shadow: 0 4px 12px var(--primary-shadow);
        }
        
        .logo-container .logo-icon i {
            font-size: 28px;
            color: white;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .login-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
            letter-spacing: -0.8px;
        }
        
        .login-header p {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            letter-spacing: -0.2px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
            letter-spacing: -0.2px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: inherit;
            color: white;
            background: rgba(255, 255, 255, 0.1);
            font-weight: 500;
            letter-spacing: -0.2px;
        }
        
        .form-input:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px var(--primary-shadow-light);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
        }
        
        .forgot-password {
            text-align: right;
            margin-bottom: 24px;
        }
        
        .forgot-password a {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            letter-spacing: -0.2px;
        }
        
        .forgot-password a:hover {
            color: white;
            transform: translateX(2px);
        }
        
        .btn-signin {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 6px 20px var(--primary-shadow);
            margin-bottom: 24px;
            letter-spacing: -0.3px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-signin:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px var(--primary-shadow-hover);
        }
        
        .btn-signin:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px var(--primary-shadow);
        }
        
        .btn-signin:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .signup-link {
            text-align: center;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            letter-spacing: -0.2px;
        }
        
        .signup-link a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 800;
            transition: all 0.3s ease;
        }
        
        .signup-link a:hover {
            color: white;
            text-decoration: underline;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-container {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .login-header {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            animation-delay: 0.1s;
            animation-fill-mode: both;
        }
        
        .form-group {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            animation-fill-mode: both;
        }
        
        .form-group:nth-child(1) { animation-delay: 0.2s; }
        .form-group:nth-child(2) { animation-delay: 0.3s; }
        
        .forgot-password {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            animation-delay: 0.4s;
            animation-fill-mode: both;
        }
        
        .btn-signin {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            animation-delay: 0.5s;
            animation-fill-mode: both;
        }
        
        .signup-link {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            animation-delay: 0.6s;
            animation-fill-mode: both;
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
                border-radius: 16px;
            }
            
            .login-header h1 {
                font-size: 24px;
            }
            
            .bg-circle-1, .bg-circle-2 {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-decoration bg-circle-1"></div>
    <div class="bg-decoration bg-circle-2"></div>
    <div class="bg-decoration bg-circle-3"></div>
    
    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-container">
                @php
                    $logoUrl = \App\Models\SystemSetting::get('hospital_logo', '');
                    $hospitalName = \App\Models\SystemSetting::get('hospital_name', config('app.name', 'DuncoHMS'));
                    $logoPath = null;
                    
                    // Handle logo URL from SystemSetting (stored as /storage/path or full URL)
                    if ($logoUrl) {
                        if (str_starts_with($logoUrl, 'http')) {
                            $logoPath = $logoUrl; // Full URL
                        } elseif (str_starts_with($logoUrl, '/storage/')) {
                            $logoPath = asset($logoUrl); // Storage path
                        } elseif (str_starts_with($logoUrl, '/')) {
                            $logoPath = asset($logoUrl); // Absolute path
                        } else {
                            $logoPath = asset('storage/' . $logoUrl); // Relative storage path
                        }
                    }
                @endphp
                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="{{ $hospitalName }} Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-icon" style="display: none;">
                        <i class="fas fa-hospital"></i>
                    </div>
                @elseif(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="{{ $hospitalName }} Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-icon" style="display: none;">
                        <i class="fas fa-hospital"></i>
                    </div>
                @elseif(file_exists(public_path('logo.png')))
                    <img src="{{ asset('logo.png') }}" alt="{{ $hospitalName }} Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-icon" style="display: none;">
                        <i class="fas fa-hospital"></i>
                    </div>
                @else
                    <div class="logo-icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 8px; letter-spacing: -0.5px;">{{ $hospitalName }}</div>
                @endif
            </div>
            
            <!-- Header -->
            <div class="login-header">
                <h1>Welcome back</h1>
                <p>Sign in to your account to continue</p>
            </div>
            
            <!-- Login Form -->
            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        class="form-input" 
                        placeholder="Enter your email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                    @error('email')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 8px; font-weight: 600;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        class="form-input" 
                        placeholder="Enter your password"
                        required
                    >
                    @error('password')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 8px; font-weight: 600;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                </div>
                
                <button type="submit" class="btn-signin" id="loginBtn">
                    Sign in to your account
                </button>
                
                <div class="signup-link">
                    Don't have an account? <a href="{{ route('register') }}">Sign up here</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Form submission handling - let form submit normally for web authentication
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.textContent = 'Signing in...';
            // Don't prevent default - let it submit normally to auth.php route
        });
        
        // Parallax effect for background circles
        window.addEventListener('mousemove', function(e) {
            const circles = document.querySelectorAll('.bg-decoration');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            circles.forEach((circle, index) => {
                const speed = (index + 1) * 10;
                const xMove = (x - 0.5) * speed;
                const yMove = (y - 0.5) * speed;
                circle.style.transform = `translate(${xMove}px, ${yMove}px)`;
            });
        });
    </script>
</body>
</html>
