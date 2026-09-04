<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Login' }} - {{ config('app.name', 'Dunco HMS') }}</title>
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
        @keyframes heroFadeUp { to { opacity: 1; transform: translateY(0); } }
        .input-focus:focus { border-color: #0097A7; box-shadow: 0 0 0 3px rgba(0, 151, 167, 0.1); }
    </style>
</head>
<body>
    {{ $slot }}
</body>
</html>
