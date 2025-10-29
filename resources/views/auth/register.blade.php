<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Create your account for {{ config('app.name') }} - Hospital Management System">
    
    <title>Register - {{ config('app.name', 'Hospital Management System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }
        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform:translateY(-20px); }
        }
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .strength-weak { background-color: #ef4444; width: 25%; }
        .strength-fair { background-color: #f59e0b; width: 50%; }
        .strength-good { background-color: #3b82f6; width: 75%; }
        .strength-strong { background-color: #10b981; width: 100%; }
        .error-state {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }
        .success-state {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
        }
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .tooltip {
            position: relative;
            display: inline-block;
        }
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: #374151;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
        }
        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
        @media (max-width: 1024px) {
            .split-layout {
                flex-direction: column;
            }
            .left-section {
                display: none;
            }
            .right-section {
                width: 100%;
                min-height: 100vh;
            }
        }
    </style>
</head>
<body class="h-full gradient-bg">
    <div class="min-h-full flex split-layout">
        <!-- Left Side - Branding -->
        <div class="left-section hidden lg:flex lg:w-1/2 xl:w-2/3 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="4"/></g></svg>');"></div>
            </div>
            
            <!-- Floating Elements -->
            <div class="absolute top-20 left-20 w-32 h-32 bg-white bg-opacity-10 rounded-full floating-animation"></div>
            <div class="absolute top-40 right-32 w-24 h-24 bg-white bg-opacity-5 rounded-full floating-animation" style="animation-delay: -2s;"></div>
            <div class="absolute bottom-32 left-32 w-40 h-40 bg-white bg-opacity-5 rounded-full floating-animation" style="animation-delay: -4s;"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center px-12 py-16 text-white">
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-user-plus text-3xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">Join Our Platform</h1>
                            <p class="text-white text-opacity-80">Create Your Account Today</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-8">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-rocket text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Get Started Quickly</h3>
                            <p class="text-white text-opacity-80">Create your account in minutes and start managing your healthcare operations.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Secure & Compliant</h3>
                            <p class="text-white text-opacity-80">HIPAA compliant with enterprise-grade security and data protection.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-headset text-xl"></i>
                        </div>
        <div>
                            <h3 class="text-xl font-semibold mb-2">24/7 Support</h3>
                            <p class="text-white text-opacity-80">Get help whenever you need it with our dedicated support team.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="right-section flex-1 flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mx-auto w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4">
                        <i class="fas fa-user-plus text-2xl text-white"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
                    <p class="mt-2 text-sm text-gray-600">Join thousands of healthcare professionals</p>
                </div>
                
                <!-- Registration Form -->
                <div class="bg-white py-8 px-6 shadow-2xl rounded-2xl border border-gray-100">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-gray-400"></i>Full Name
                            </label>
                            <div class="relative">
                                <input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    value="{{ old('name') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('name') error-state @enderror"
                                    placeholder="Enter your full name"
                                    oninput="validateName(this)"
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            </div>
                            <div id="name-error" class="mt-2 text-sm text-red-600 flex items-center hidden">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span id="name-error-text"></span>
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>Email Address
                            </label>
                            <div class="relative">
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autocomplete="email"
                                    class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('email') error-state @enderror"
                                    placeholder="Enter your email address"
                                    oninput="validateEmail(this)"
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                            </div>
                            <div id="email-error" class="mt-2 text-sm text-red-600 flex items-center hidden">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span id="email-error-text"></span>
                            </div>
                            <div id="email-success" class="mt-2 text-sm text-green-600 flex items-center hidden">
                                <i class="fas fa-check-circle mr-1"></i>
                                <span>Valid email address</span>
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                            </label>
                            
                            <!-- Password Requirements -->
                            <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm font-medium text-blue-800 mb-2">Password Requirements:</p>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li id="req-length" class="flex items-center">
                                        <i class="fas fa-circle text-gray-300 mr-2"></i>
                                        At least 8 characters long
                                    </li>
                                    <li id="req-lowercase" class="flex items-center">
                                        <i class="fas fa-circle text-gray-300 mr-2"></i>
                                        One lowercase letter
                                    </li>
                                    <li id="req-uppercase" class="flex items-center">
                                        <i class="fas fa-circle text-gray-300 mr-2"></i>
                                        One uppercase letter
                                    </li>
                                    <li id="req-number" class="flex items-center">
                                        <i class="fas fa-circle text-gray-300 mr-2"></i>
                                        One number
                                    </li>
                                    <li id="req-special" class="flex items-center">
                                        <i class="fas fa-circle text-gray-300 mr-2"></i>
                                        One special character
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="relative">
                                <input 
                                    id="password" 
                                    name="password" 
                            type="password"
                                    required 
                                    autocomplete="new-password"
                                    class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('password') error-state @enderror"
                                    placeholder="Create a strong password"
                                    oninput="checkPasswordStrength(this.value)"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password')"
                                    class="tooltip absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200"
                                >
                                    <i id="password-toggle-icon" class="fas fa-eye"></i>
                                    <span class="tooltiptext">Toggle password visibility</span>
                                </button>
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-2">
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-1">
                                        <div id="password-strength" class="password-strength"></div>
                                    </div>
                                    <span id="password-strength-text" class="text-xs text-gray-500">Enter password</span>
                                </div>
        </div>

                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i>Confirm Password
                            </label>
                            <div class="relative">
                                <input 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                            type="password"
                                    required 
                                    autocomplete="new-password"
                                    class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('password_confirmation') border-red-300 @enderror"
                                    placeholder="Confirm your password"
                                    oninput="checkPasswordMatch()"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password_confirmation')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200"
                                >
                                    <i id="password_confirmation-toggle-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Password Match Indicator -->
                            <div id="password-match" class="mt-2 text-sm hidden">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                <span class="text-green-600">Passwords match</span>
                            </div>
                            
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input 
                                    id="terms" 
                                    name="terms" 
                                    type="checkbox" 
                                    required
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                >
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="text-gray-700">
                                    I agree to the 
                                    <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium underline">Terms of Service</a> 
                                    and 
                                    <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium underline">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div>
                            <button 
                                type="submit" 
                                class="btn-hover group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200"
                            >
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-user-plus text-indigo-500 group-hover:text-indigo-400 transition duration-200"></i>
                                </span>
                                Create Account
                            </button>
                        </div>
                        
                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Or continue with</span>
                            </div>
        </div>

                        <!-- Social Login Buttons -->
                        <div class="grid grid-cols-2 gap-3">
                            <button 
                                type="button" 
                                class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition duration-200"
                            >
                                <i class="fab fa-google text-red-500 mr-2"></i>
                                Google
                            </button>
                            <button 
                                type="button" 
                                class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition duration-200"
                            >
                                <i class="fab fa-microsoft text-blue-500 mr-2"></i>
                                Microsoft
                            </button>
                        </div>
                    </form>
                    
                    <!-- Login Link -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-200 hover:underline">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-white text-opacity-80 mb-3">
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                    <div class="flex justify-center space-x-6 text-sm">
                        <a href="#" class="text-white text-opacity-80 hover:text-white hover:text-opacity-100 transition duration-200 font-medium">Privacy Policy</a>
                        <a href="#" class="text-white text-opacity-80 hover:text-white hover:text-opacity-100 transition duration-200 font-medium">Terms of Service</a>
                        <a href="#" class="text-white text-opacity-80 hover:text-white hover:text-opacity-100 transition duration-200 font-medium">Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + '-toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('password-strength');
            const strengthText = document.getElementById('password-strength-text');
            
            // Check individual requirements
            const hasLength = password.length >= 8;
            const hasLowercase = /[a-z]/.test(password);
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[^a-zA-Z0-9]/.test(password);
            
            // Update requirement indicators
            updateRequirement('req-length', hasLength);
            updateRequirement('req-lowercase', hasLowercase);
            updateRequirement('req-uppercase', hasUppercase);
            updateRequirement('req-number', hasNumber);
            updateRequirement('req-special', hasSpecial);
            
            // Calculate strength
            let strength = 0;
            if (hasLength) strength++;
            if (hasLowercase) strength++;
            if (hasUppercase) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;
            
            strengthBar.className = 'password-strength';
            
            if (password.length === 0) {
                strengthBar.style.width = '0%';
                strengthText.textContent = 'Enter password';
                strengthText.className = 'text-xs text-gray-500';
            } else if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Weak';
                strengthText.className = 'text-xs text-red-500';
            } else if (strength === 3) {
                strengthBar.classList.add('strength-fair');
                strengthText.textContent = 'Fair';
                strengthText.className = 'text-xs text-yellow-500';
            } else if (strength === 4) {
                strengthBar.classList.add('strength-good');
                strengthText.textContent = 'Good';
                strengthText.className = 'text-xs text-blue-500';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Strong';
                strengthText.className = 'text-xs text-green-500';
            }
        }
        
        function updateRequirement(elementId, isValid) {
            const element = document.getElementById(elementId);
            const icon = element.querySelector('i');
            
            if (isValid) {
                icon.className = 'fas fa-check-circle text-green-500 mr-2';
                element.classList.add('text-green-700');
                element.classList.remove('text-blue-700');
            } else {
                icon.className = 'fas fa-circle text-gray-300 mr-2';
                element.classList.remove('text-green-700');
                element.classList.add('text-blue-700');
            }
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchIndicator = document.getElementById('password-match');
            
            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchIndicator.classList.remove('hidden');
                    matchIndicator.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-1"></i><span class="text-green-600">Passwords match</span>';
                } else {
                    matchIndicator.classList.remove('hidden');
                    matchIndicator.innerHTML = '<i class="fas fa-times-circle text-red-500 mr-1"></i><span class="text-red-600">Passwords do not match</span>';
                }
            } else {
                matchIndicator.classList.add('hidden');
            }
        }
        
        // Add real-time validation for all fields
        function validateName(input) {
            const name = input.value.trim();
            const errorDiv = document.getElementById('name-error');
            
            if (name === '') {
                hideNameMessages();
                input.classList.remove('error-state', 'success-state');
                return;
            }
            
            if (name.length < 2) {
                showError(input, errorDiv, null, 'Name must be at least 2 characters long');
            } else {
                showSuccess(input, null, errorDiv);
            }
        }
        
        function validateEmail(input) {
            const email = input.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const errorDiv = document.getElementById('email-error');
            const successDiv = document.getElementById('email-success');
            
            if (email === '') {
                hideEmailMessages();
                input.classList.remove('error-state', 'success-state');
                return;
            }
            
            if (emailRegex.test(email)) {
                showSuccess(input, successDiv, errorDiv);
            } else {
                showError(input, errorDiv, successDiv, 'Please enter a valid email address');
            }
        }
        
        function showError(input, errorDiv, successDiv, message) {
            input.classList.remove('success-state');
            input.classList.add('error-state');
            
            if (errorDiv) {
                errorDiv.querySelector('span').textContent = message;
                errorDiv.classList.remove('hidden');
            }
            
            if (successDiv) {
                successDiv.classList.add('hidden');
            }
        }
        
        function showSuccess(input, successDiv, errorDiv) {
            input.classList.remove('error-state');
            input.classList.add('success-state');
            
            if (successDiv) {
                successDiv.classList.remove('hidden');
            }
            
            if (errorDiv) {
                errorDiv.classList.add('hidden');
            }
        }
        
        function hideNameMessages() {
            const errorDiv = document.getElementById('name-error');
            if (errorDiv) errorDiv.classList.add('hidden');
        }
        
        function hideEmailMessages() {
            const errorDiv = document.getElementById('email-error');
            const successDiv = document.getElementById('email-success');
            if (errorDiv) errorDiv.classList.add('hidden');
            if (successDiv) successDiv.classList.add('hidden');
        }
        
        // Add loading state to form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            const buttonText = submitButton.querySelector('span');
            const buttonIcon = submitButton.querySelector('i');
            
            // Show loading state
            buttonText.textContent = 'Creating account...';
            buttonIcon.className = 'fas fa-spinner loading-spinner mr-2';
            submitButton.disabled = true;
            submitButton.classList.add('opacity-75');
        });
        
        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.bg-white');
            form.style.opacity = '0';
            form.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                form.style.transition = 'all 0.6s ease-out';
                form.style.opacity = '1';
                form.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>