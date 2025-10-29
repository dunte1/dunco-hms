<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Verify your email for {{ config('app.name') }} - Hospital Management System">
    
    <title>Verify Email - {{ config('app.name', 'Hospital Management System') }}</title>
    
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
        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body class="h-full gradient-bg">
    <div class="min-h-full flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 xl:w-2/3 relative overflow-hidden">
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
                            <i class="fas fa-envelope-open text-3xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">Email Verification</h1>
                            <p class="text-white text-opacity-80">Secure Your Account</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-8">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Enhanced Security</h3>
                            <p class="text-white text-opacity-80">Email verification adds an extra layer of security to your account.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bell text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Important Notifications</h3>
                            <p class="text-white text-opacity-80">Receive critical updates and notifications about your account.</p>
                        </div>
    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-key text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Password Recovery</h3>
                            <p class="text-white text-opacity-80">Use your verified email to reset your password if needed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Verification Form -->
        <div class="flex-1 flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mx-auto w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4">
                        <i class="fas fa-envelope-open text-2xl text-white"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Verify Your Email</h2>
                    <p class="mt-2 text-sm text-gray-600">We've sent a verification link to your email address</p>
                </div>
                
                <!-- Verification Form -->
                <div class="bg-white py-8 px-6 shadow-2xl rounded-2xl border border-gray-100">
                    <!-- Status Messages -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-800">A new verification link has been sent to your email address.</p>
                                </div>
                            </div>
        </div>
    @endif

                    <div class="text-center space-y-6">
                        <!-- Email Icon -->
                        <div class="mx-auto w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-3xl text-indigo-600"></i>
                        </div>
                        
                        <!-- Instructions -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Check Your Email</h3>
                            <p class="text-sm text-gray-600">
                                We've sent a verification link to <strong>{{ Auth::user()->email }}</strong>
                            </p>
                        </div>
                        
                        <!-- Resend Form -->
                        <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
            @csrf

            <div>
                                <button 
                                    type="submit" 
                                    class="btn-hover group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200"
                                >
                                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                        <i class="fas fa-paper-plane text-indigo-500 group-hover:text-indigo-400 transition duration-200"></i>
                                    </span>
                                    Resend Verification Email
                                </button>
            </div>
        </form>

                        <!-- Alternative Actions -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                <p class="mb-2">Didn't receive the email?</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• Check your spam/junk folder</li>
                                    <li>• Make sure the email address is correct</li>
                                    <li>• Wait a few minutes and try again</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Logout Option -->
                        <div class="pt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
                                <button 
                                    type="submit" 
                                    class="text-sm text-gray-500 hover:text-gray-700 transition duration-200"
                                >
                                    <i class="fas fa-sign-out-alt mr-1"></i>
                                    Sign out and use a different email
            </button>
        </form>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-white text-opacity-60">
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Add loading state to form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending verification email...';
            submitButton.disabled = true;
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
        
        // Auto-refresh page every 30 seconds to check if email was verified
        setTimeout(() => {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>