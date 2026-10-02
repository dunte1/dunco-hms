<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - {{ \App\Models\SystemSetting::get('hospital_name', 'Dunco HMS') }}</title>
    @php
        $primaryColor = \App\Models\SystemSetting::get('primary_color', '#000075');
        $secondaryColor = \App\Models\SystemSetting::get('secondary_color', '#00001A');
        $hospitalName = \App\Models\SystemSetting::get('hospital_name', 'Dunco HMS');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        .auth-entrance { animation: fadeInScale 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeInScale { from { opacity: 0; transform: scale(0.97); } to { opacity: 1; transform: scale(1); } }
        .hero-fade-up { opacity: 0; transform: translateY(30px); animation: heroFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .hero-fade-up-delay-1 { animation-delay: 0.15s; }
        .hero-fade-up-delay-2 { animation-delay: 0.3s; }
        .hero-fade-up-delay-3 { animation-delay: 0.45s; }
        @keyframes heroFadeUp { to { opacity: 1; transform: translateY(0); } }
        .left-panel { background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%); }
        .icon-circle { width: 60px; height: 60px; border-radius: 50%; background: {{ $primaryColor }}15; display: inline-flex; align-items: center; justify-content: center; }
    </style>
</head>
<body class="h-full bg-gray-50">
    <div class="flex min-h-screen">
        {{-- Left Panel --}}
        <div class="left-panel hidden lg:flex w-1/2 items-center justify-center">
            <div class="max-w-md px-8 text-center">
                <div class="hero-fade-up mb-8 flex justify-center">
                    <div class="w-16 h-16 bg-white/15 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <h1 class="hero-fade-up hero-fade-up-delay-1 text-3xl font-bold text-white">{{ $hospitalName }}</h1>
                <p class="hero-fade-up hero-fade-up-delay-2 mt-3 text-lg text-white/70">Email Verification</p>
                <div class="hero-fade-up hero-fade-up-delay-3 mt-10 space-y-4 text-left">
                    <div class="flex items-center gap-3 text-white/80">
                        <i class="fa fa-check-circle w-5"></i>
                        <span>Verify your email address</span>
                    </div>
                    <div class="flex items-center gap-3 text-white/80">
                        <i class="fa fa-check-circle w-5"></i>
                        <span>Secure your account</span>
                    </div>
                    <div class="flex items-center gap-3 text-white/80">
                        <i class="fa fa-check-circle w-5"></i>
                        <span>Access all features</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="flex w-full items-center justify-center bg-white p-6 lg:w-1/2">
            <div class="w-full max-w-md auth-entrance">
                {{-- Mobile Logo --}}
                <div class="mb-8 text-center lg:hidden">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl" style="background: linear-gradient(135deg, {{ $primaryColor }}, {{ $secondaryColor }});">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h2 class="mt-3 text-xl font-bold text-gray-900">{{ $hospitalName }}</h2>
                </div>

                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Verify your email</h1>
                    <p class="mt-2 text-sm text-gray-500">
                        We've sent a verification link to<br>
                        <span class="font-semibold text-gray-700">{{ Auth::user()->email ?? 'your email' }}</span>
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-center text-sm text-green-700">
                        A new verification link has been sent to your email.
                    </div>
                @endif

                <div class="space-y-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="h-11 w-full rounded-lg px-4 text-sm font-semibold text-white shadow-sm transition-all hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2" style="background: {{ $primaryColor }}; --tw-ring-color: {{ $primaryColor }};">
                            Resend verification email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2" style="--tw-ring-color: {{ $primaryColor }};">
                            Log out
                        </button>
                    </form>
                </div>

                <p class="mt-8 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} {{ $hospitalName }}. Powered by <a href="https://duncowebsolutions.co.ke" style="color: {{ $primaryColor }};">Dunco Web Solutions</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
