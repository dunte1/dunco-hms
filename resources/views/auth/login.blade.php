<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - HealthCare Pro</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 25%, #3b82f6 50%, #60a5fa 75%, #93c5fd 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: flex;
            align-items: center;
            justify-content: center;
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
        
        /* Flip Card Container */
        .flip-card-container {
            perspective: 1000px;
            width: 100%;
            max-width: 450px;
            height: 520px;
            position: relative;
            z-index: 10;
        }
        
        .flip-card {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .flip-card.flipped {
            transform: rotateY(180deg);
        }
        
        .card-face {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
            padding: 32px 28px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }
        
        .card-back {
            transform: rotateY(180deg);
        }
        
        /* Brand Logo */
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .brand-icon i {
            font-size: 20px;
            color: white;
        }
        
        .brand-text h1 {
            font-size: 18px;
            font-weight: 800;
            color: white;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        .brand-text span {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            letter-spacing: -0.1px;
        }
        
        /* Header Styles */
        .card-header {
            text-align: center;
            margin-bottom: 24px;
        }
        
        .card-header h2 {
            font-size: 26px;
            font-weight: 800;
            color: white;
            margin-bottom: 6px;
            letter-spacing: -0.8px;
        }
        
        .card-header p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            letter-spacing: -0.2px;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 16px;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
            letter-spacing: -0.2px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            font-size: 13px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: 'Inter', sans-serif;
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
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
        }
        
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.6);
            font-size: 15px;
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        .form-input:focus + .input-icon {
            color: #3b82f6;
            transform: translateY(-50%) scale(1.1);
        }
        
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
            padding: 4px;
        }
        
        .password-toggle:hover {
            color: #3b82f6;
            transform: translateY(-50%) scale(1.15);
        }
        
        /* Error Messages */
        .error-message {
            display: none;
            margin-top: 8px;
            font-size: 12px;
            color: #ef4444;
            font-weight: 600;
            animation: shake 0.5s ease-in-out;
        }
        
        .error-message.show {
            display: block;
        }
        
        .form-input.error {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }
        
        .form-input.error:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        /* Form Footer */
        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            animation-delay: 0.5s;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .checkbox {
            width: 18px;
            height: 18px;
            accent-color: #3b82f6;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .checkbox-label {
            font-size: 14px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: -0.2px;
            user-select: none;
        }
        
        .forgot-link {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            letter-spacing: -0.2px;
        }
        
        .forgot-link:hover {
            color: white;
            transform: translateX(2px);
        }
        
        /* Primary Button */
        .btn-primary {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.35);
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            animation-delay: 0.6s;
            letter-spacing: -0.3px;
            position: relative;
            overflow: hidden;
            margin-bottom: 16px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(59, 130, 246, 0.45);
        }
        
        .btn-primary:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }
        
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 14px 0;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            font-weight: 600;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            animation-delay: 0.7s;
            letter-spacing: -0.2px;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .divider span {
            padding: 0 16px;
        }
        
        /* Social Buttons */
        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .btn-social {
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            color: white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            letter-spacing: -0.3px;
        }
        
        .btn-social:hover {
            border-color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .btn-social i {
            font-size: 16px;
        }
        
        /* Toggle Link */
        .toggle-link {
            text-align: center;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            animation-delay: 0.8s;
            letter-spacing: -0.2px;
            margin-bottom: 16px;
        }
        
        .toggle-link a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 800;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .toggle-link a:hover {
            color: white;
            text-decoration: underline;
        }
        
        /* Footer */
        .footer-links {
            text-align: center;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1.5px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            animation-delay: 0.9s;
        }
        
        .footer-text {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 6px;
            font-weight: 600;
            letter-spacing: -0.1px;
        }
        
        .footer-nav {
            display: flex;
            justify-content: center;
            gap: 14px;
            font-size: 11px;
        }
        
        .footer-nav a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            letter-spacing: -0.2px;
        }
        
        .footer-nav a:hover {
            color: white;
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
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .flip-card-container {
                max-width: 90%;
                height: auto;
                min-height: 480px;
            }
            
            .card-face {
                padding: 28px 24px;
            }
            
            .card-header h2 {
                font-size: 22px;
            }
            
            .social-buttons {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .card-face {
                padding: 24px 20px;
            }
            
            .card-header h2 {
                font-size: 20px;
            }
            
            .form-input {
                padding: 10px 12px 10px 38px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-decoration bg-circle-1"></div>
    <div class="bg-decoration bg-circle-2"></div>
    <div class="bg-decoration bg-circle-3"></div>
    
    <!-- Flip Card Container -->
    <div class="flip-card-container">
        <div class="flip-card" id="flipCard">
            <!-- Login Card (Front) -->
            <div class="card-face">
                <div class="brand-logo">
                    <div class="brand-icon">
                        <i class="fas fa-hospital"></i>
                        </div>
                    <div class="brand-text">
                        <h1>HealthCare Pro</h1>
                        <span>Advanced Healthcare Solutions</span>
                    </div>
                </div>
                
                <div class="card-header">
                    <h2>Welcome back</h2>
                    <p>Sign in to your account to continue</p>
                </div>
                
                <form id="loginForm">
                    <div class="form-group">
                        <label class="form-label" for="loginEmail">Email Address</label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                id="loginEmail" 
                                class="form-input" 
                                placeholder="Enter your email"
                                required
                            >
                            <i class="input-icon fas fa-envelope"></i>
                        </div>
                        <div class="error-message" id="loginEmailError">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Please enter a valid email address</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="loginPassword">Password</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                id="loginPassword" 
                                class="form-input" 
                                placeholder="Enter your password"
                                required
                            >
                            <i class="input-icon fas fa-lock"></i>
                            <i class="password-toggle fas fa-eye" id="toggleLoginPassword"></i>
                        </div>
                        <div class="error-message" id="loginPasswordError">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Password must be at least 6 characters</span>
                        </div>
                    </div>
                    
                    <div class="form-footer">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="remember" class="checkbox">
                            <label for="remember" class="checkbox-label">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    </div>
                    
                    <button type="submit" class="btn-primary" id="loginBtn">
                        Sign in to your account
                    </button>
                    
                    <div class="divider">
                        <span>Or continue with</span>
                        </div>
                    
                    <div class="social-buttons">
                        <button type="button" class="btn-social">
                            <i class="fab fa-google" style="color: white;"></i>
                            Google
                        </button>
                        <button type="button" class="btn-social">
                            <i class="fab fa-microsoft" style="color: white;"></i>
                            Microsoft
                        </button>
                    </div>
                    
                    <div class="toggle-link">
                        Don't have an account? <a href="#" onclick="flipCard()">Sign up here</a>
                </div>
                </form>
                
                <div class="footer-links">
                    <p class="footer-text">© 2025 HealthCare Pro. All rights reserved.</p>
                    <div class="footer-nav">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Support</a>
                    </div>
                </div>
            </div>
            
            <!-- Register Card (Back) -->
            <div class="card-face card-back">
                <div class="brand-logo">
                    <div class="brand-icon">
                        <i class="fas fa-hospital"></i>
        </div>
                    <div class="brand-text">
                        <h1>HealthCare Pro</h1>
                        <span>Advanced Healthcare Solutions</span>
                    </div>
                </div>
                
                <div class="card-header">
                    <h2>Create Account</h2>
                    <p>Join HealthCare Pro to get started</p>
                                </div>
                
                <form id="registerForm">
                    <div class="form-group">
                        <label class="form-label" for="registerName">Full Name</label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                id="registerName" 
                                class="form-input" 
                                placeholder="Enter your full name"
                                required
                            >
                            <i class="input-icon fas fa-user"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="registerEmail">Email Address</label>
                        <div class="input-wrapper">
                                <input 
                                    type="email" 
                                id="registerEmail" 
                                class="form-input" 
                                placeholder="Enter your email"
                                    required 
                            >
                            <i class="input-icon fas fa-envelope"></i>
                        </div>
        </div>

                    <div class="form-group">
                        <label class="form-label" for="registerPassword">Password</label>
                        <div class="input-wrapper">
                                <input 
                                type="password" 
                                id="registerPassword" 
                                class="form-input" 
                                placeholder="Create a password"
                                required
                            >
                            <i class="input-icon fas fa-lock"></i>
                            <i class="password-toggle fas fa-eye" id="toggleRegisterPassword"></i>
        </div>
                        </div>
                        
                    <div class="form-group">
                        <label class="form-label" for="confirmPassword">Confirm Password</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                id="confirmPassword" 
                                class="form-input" 
                                placeholder="Confirm your password"
                                required
                            >
                            <i class="input-icon fas fa-lock"></i>
                        </div>
                        </div>
                        
                    <div class="form-footer">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="agreeTerms" class="checkbox" required>
                            <label for="agreeTerms" class="checkbox-label">I agree to the terms and conditions</label>
                            </div>
                            </div>
                    
                    <button type="submit" class="btn-primary" id="registerBtn">
                        Create Account
                    </button>
                    
                    <div class="divider">
                        <span>Or continue with</span>
                        </div>
                        
                    <div class="social-buttons">
                        <button type="button" class="btn-social">
                            <i class="fab fa-google" style="color: white;"></i>
                                Google
                            </button>
                        <button type="button" class="btn-social">
                            <i class="fab fa-microsoft" style="color: white;"></i>
                                Microsoft
                            </button>
                        </div>
                    
                    <div class="toggle-link">
                        Already have an account? <a href="#" onclick="flipCard()">Sign in here</a>
                    </div>
                </form>
                
                <div class="footer-links">
                    <p class="footer-text">© 2025 HealthCare Pro. All rights reserved.</p>
                    <div class="footer-nav">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Flip card functionality
        function flipCard() {
            const flipCard = document.getElementById('flipCard');
            flipCard.classList.toggle('flipped');
        }
        
        // Password toggle functionality
        document.getElementById('toggleLoginPassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('loginPassword');
            const toggleIcon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
        
        document.getElementById('toggleRegisterPassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('registerPassword');
            const toggleIcon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
        
        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            
            if (email && password) {
            // Show loading state
                const submitBtn = document.getElementById('loginBtn');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Signing in...';
                submitBtn.disabled = true;
                
                // Submit the form to Laravel backend
                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', password);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
                
                fetch('/login', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Redirect to dashboard on successful login
                        window.location.href = '/dashboard';
                    } else {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Login failed');
                        });
                    }
                })
                .catch(error => {
                    // Show error message
                    alert('Login failed: ' + error.message);
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
            }
        });
        
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const agreeTerms = document.getElementById('agreeTerms').checked;
            
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            if (!agreeTerms) {
                alert('Please agree to the terms and conditions!');
                return;
            }
            
            if (name && email && password && confirmPassword) {
                // Show loading state
                const submitBtn = document.getElementById('registerBtn');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Creating Account...';
                submitBtn.disabled = true;
                
                // Submit the form to Laravel backend
                const formData = new FormData();
                formData.append('name', name);
                formData.append('email', email);
                formData.append('password', password);
                formData.append('password_confirmation', confirmPassword);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
                
                fetch('/register', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Redirect to dashboard on successful registration
                        window.location.href = '/dashboard';
                    } else {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Registration failed');
                        });
                    }
                })
                .catch(error => {
                    // Show error message
                    alert('Registration failed: ' + error.message);
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
            }
        });
        
        // Enhanced form validation
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        function validatePassword(password) {
            return password.length >= 6;
        }
        
        function showError(inputId, errorId, message) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);
            input.classList.add('error');
            error.querySelector('span').textContent = message;
            error.classList.add('show');
        }
        
        function hideError(inputId, errorId) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);
            input.classList.remove('error');
            error.classList.remove('show');
        }
        
        // Real-time validation
        document.getElementById('loginEmail').addEventListener('blur', function() {
            if (this.value && !validateEmail(this.value)) {
                showError('loginEmail', 'loginEmailError', 'Please enter a valid email address');
            } else {
                hideError('loginEmail', 'loginEmailError');
            }
        });
        
        document.getElementById('loginPassword').addEventListener('input', function() {
            if (this.value && !validatePassword(this.value)) {
                showError('loginPassword', 'loginPasswordError', 'Password must be at least 6 characters');
            } else {
                hideError('loginPassword', 'loginPasswordError');
            }
        });
        
        // Register form validation
        document.getElementById('registerEmail').addEventListener('blur', function() {
            if (this.value && !validateEmail(this.value)) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
            }
        });
        
        document.getElementById('registerPassword').addEventListener('input', function() {
            if (this.value && !validatePassword(this.value)) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
            }
        });
        
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('registerPassword').value;
            if (this.value && this.value !== password) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
            }
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
        
        // Ripple effect for buttons
        function createRipple(event) {
            const button = event.currentTarget;
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.6)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s ease-out';
            ripple.style.pointerEvents = 'none';
            
            button.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        }
        
        document.querySelectorAll('.btn-primary, .btn-social').forEach(button => {
            button.addEventListener('click', createRipple);
        });
        
        // Add ripple animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>