<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Dunco HMS') }}</title>
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
    </style>
</head>
<body class="h-full bg-gray-50">
    <div class="flex min-h-screen">
        {{-- Left Panel: Brand --}}
        <div class="hidden w-1/2 items-center justify-center bg-gradient-to-br from-cyan-600 via-cyan-700 to-teal-800 lg:flex">
            <div class="max-w-md px-8 text-center">
                @php
                    $hospitalName = \App\Models\SystemSetting::get('hospital_name', config('app.name', 'Dunco HMS'));
                    $logoUrl = \App\Models\SystemSetting::get('hospital_logo', '');
                    $logoPath = null;
                    if ($logoUrl) {
                        if (str_starts_with($logoUrl, 'http')) $logoPath = $logoUrl;
                        elseif (str_starts_with($logoUrl, '/storage/')) $logoPath = asset($logoUrl);
                        elseif (str_starts_with($logoUrl, '/')) $logoPath = asset($logoUrl);
                        else $logoPath = asset('storage/' . $logoUrl);
                    }
                @endphp
                <div class="hero-fade-up mb-8 flex justify-center">
                    @if($logoPath)
                        <img src="{{ $logoPath }}" alt="{{ $hospitalName }}" class="h-16 w-auto rounded-2xl bg-white/15 p-2 backdrop-blur-sm">
                    @else
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-sm">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    @endif
                </div>
                <h1 class="hero-fade-up hero-fade-up-delay-1 text-3xl font-bold text-white">{{ $hospitalName }}</h1>
                <p class="hero-fade-up hero-fade-up-delay-2 mt-3 text-lg text-cyan-100">Hospital Management System</p>
                <div class="hero-fade-up hero-fade-up-delay-3 mt-10 space-y-4 text-left">
                    <div class="flex items-center gap-3 text-cyan-100">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Complete patient management & clinical workflows</span>
                    </div>
                    <div class="flex items-center gap-3 text-cyan-100">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Integrated billing, pharmacy & laboratory</span>
                    </div>
                    <div class="flex items-center gap-3 text-cyan-100">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>SHA/insurance integration & real-time reporting</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel: Form --}}
        <div class="flex w-full items-center justify-center bg-white p-6 lg:w-1/2">
            <div class="w-full max-w-md auth-entrance">
                {{-- Mobile Logo --}}
                <div class="mb-8 text-center lg:hidden">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h2 class="mt-3 text-xl font-bold text-gray-900">{{ $hospitalName }}</h2>
                </div>

                {{-- Header --}}
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Sign in to your account</h1>
                    <p class="mt-2 text-sm text-gray-500">Enter your credentials to access the system</p>
                </div>

                {{-- Session Status / Errors --}}
                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                            class="input-focus h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20"
                            placeholder="you@example.com">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required
                            class="input-focus h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20"
                            placeholder="Enter your password">
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6 flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                            <span class="text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-cyan-600 transition-colors hover:text-cyan-700">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit" class="h-11 w-full rounded-lg bg-cyan-600 px-4 text-sm font-semibold text-white shadow-sm shadow-cyan-600/20 transition-all hover:bg-cyan-700 hover:shadow-md hover:shadow-cyan-600/30 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                        Sign in
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-500">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-semibold text-cyan-600 transition-colors hover:text-cyan-700">Sign up</a>
                </p>

                <p class="mt-8 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Dunco HMS') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
