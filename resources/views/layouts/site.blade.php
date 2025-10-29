<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('meta')
        <title>{{ config('app.name', 'DuncoHMS') }}</title>

        <!-- Favicon -->
        @if($themeSettings['favicon'] ?? false)
            <link rel="icon" type="image/x-icon" href="{{ $themeSettings['favicon'] }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ $themeSettings['favicon'] }}">
        @else
            <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        @endif

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js for mobile menu -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="antialiased bg-gray-50" id="top">
        <div class="bg-indigo-50 border-b text-xs text-gray-700">
            <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">
                <div class="hidden md:block">Open Hours: Mon–Fri 8:00–18:00</div>
                <div class="flex items-center gap-4">
                    <a href="tel:+254700000000" class="font-medium text-red-700">Emergency: +254 700 000 000</a>
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open=!open" class="text-gray-700" aria-haspopup="listbox" aria-expanded="false">{{ strtoupper(app()->getLocale()) }}</button>
                        <div x-show="open" @click.outside="open=false" class="absolute right-0 mt-2 bg-white border rounded shadow text-sm">
                            @foreach(['ar','de','en','es','fr','it','pt','ru','tr','zh'] as $loc)
                                <a class="block px-3 py-2 hover:bg-gray-50" href="{{ route('lang.switch', $loc) }}">{{ strtoupper($loc) }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <header class="bg-white sticky top-0 z-40 border-b" role="banner" x-data="{ mobile:false }" @open-mobile-nav.window="mobile=true">
            <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2" aria-label="{{ config('app.name', 'DuncoHMS') }} home">
                    @if($themeSettings['hospital_logo'] ?? false)
                        <img src="{{ $themeSettings['hospital_logo'] }}" alt="{{ config('app.name', 'DuncoHMS') }} Logo" class="h-9 w-auto">
                    @else
                        <div class="h-8 w-8 bg-indigo-600 rounded flex items-center justify-center">
                            <span class="text-white font-bold text-lg">D</span>
                        </div>
                    @endif
                    <span class="text-lg font-semibold">{{ config('app.name', 'DuncoHMS') }}</span>
                </a>
                <nav class="hidden md:flex items-center gap-6" aria-label="Primary">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-indigo-600 font-medium' : 'text-gray-700 hover:text-gray-900' }}">Home</a>
                    <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'text-indigo-600 font-medium' : 'text-gray-700 hover:text-gray-900' }}">Services</a>
                    <a href="{{ route('doctors') }}" class="{{ request()->routeIs('doctors') ? 'text-indigo-600 font-medium' : 'text-gray-700 hover:text-gray-900' }}">Doctors</a>
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-indigo-600 font-medium' : 'text-gray-700 hover:text-gray-900' }}">About</a>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-indigo-600 font-medium' : 'text-gray-700 hover:text-gray-900' }}">Contact</a>
                    <a href="{{ route('features') }}" class="{{ request()->routeIs('features') ? 'text-indigo-600 font-medium' : 'text-gray-700 hover:text-gray-900' }}">Our Features</a>
                    <a href="{{ route('book-appointment') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Book Appointment</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900">Register</a>
                    @endauth
                </nav>
                <div class="flex items-center gap-3 md:hidden">
                    <button class="p-2" aria-label="Open menu" @click="mobile=true">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
            <div x-show="mobile" class="md:hidden fixed inset-0 bg-black/40" @click="mobile=false"></div>
            <div x-show="mobile" class="md:hidden fixed top-0 right-0 w-64 h-full bg-white shadow-lg p-4" role="dialog" aria-label="Mobile navigation">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-semibold">Menu</span>
                    <button class="p-2" aria-label="Close menu" @click="mobile=false">✕</button>
                </div>
                <nav class="flex flex-col gap-3" aria-label="Mobile Primary">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('services') }}">Services</a>
                    <a href="{{ route('doctors') }}">Doctors</a>
                    <a href="{{ route('about') }}">About</a>
                    <a href="{{ route('contact') }}">Contact</a>
                    <a href="{{ route('features') }}">Our Features</a>
                    <a href="{{ route('book-appointment') }}" class="px-3 py-2 bg-indigo-600 text-white rounded text-center">Book Appointment</a>
                    @auth
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main role="main">
            @yield('content')
        </main>

        <footer class="bg-white border-t mt-16" role="contentinfo">
            <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-4 gap-8 text-sm">
                <div>
                    <div class="font-semibold mb-3">{{ $themeSettings['hospital_name'] ?? config('app.name', 'Dunco Hospital') }}</div>
                    <p class="text-gray-600">{{ $themeSettings['hospital_address'] ?? '123 Hospital Road' }}</p>
                    <p><a href="tel:{{ $themeSettings['hospital_phone'] ?? '+254700000000' }}" class="text-gray-700">{{ $themeSettings['hospital_phone'] ?? '+254 700 000 000' }}</a></p>
                    <p><a href="mailto:{{ $themeSettings['hospital_email'] ?? 'info@hospital.com' }}" class="text-gray-700">{{ $themeSettings['hospital_email'] ?? 'info@hospital.com' }}</a></p>
                    <div class="flex gap-3 mt-3 text-gray-600">
                        <a href="#" aria-label="Facebook">FB</a>
                        <a href="#" aria-label="X">X</a>
                        <a href="#" aria-label="Instagram">IG</a>
                        <a href="#" aria-label="LinkedIn">IN</a>
                    </div>
                </div>
                <div>
                    <div class="font-semibold mb-3">Departments</div>
                    <ul class="space-y-1 text-gray-700">
                        <li><a href="{{ route('services') }}#cardiology">Cardiology</a></li>
                        <li><a href="{{ route('services') }}#radiology">Radiology</a></li>
                        <li><a href="{{ route('services') }}#lab">Laboratory</a></li>
                        <li><a href="{{ route('services') }}#pharmacy">Pharmacy</a></li>
                    </ul>
                </div>
                <div>
                    <div class="font-semibold mb-3">Patients</div>
                    <ul class="space-y-1 text-gray-700">
                        <li><a href="{{ route('book-appointment') }}">Book Appointment</a></li>
                        <li><a href="{{ route('doctors') }}">Find a Doctor</a></li>
                        <li><a href="{{ route('contact') }}">Contact & Directions</a></li>
                        <li><a href="{{ route('features') }}">Our Features</a></li>
                    </ul>
                </div>
                <div>
                    <div class="font-semibold mb-3">Legal</div>
                    <ul class="space-y-1 text-gray-700">
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                    <form class="mt-4" aria-label="Newsletter subscribe">
                        <label class="block text-gray-700 mb-1">Subscribe</label>
                        <div class="flex gap-2">
                            <input type="email" class="border rounded p-2 flex-1" placeholder="you@example.com" aria-label="Email address">
                            <button class="px-3 py-2 bg-indigo-600 text-white rounded" type="button">Join</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="border-t">
                <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between text-xs text-gray-500">
                    <div class="text-center flex-1">
                        <span>© {{ date('Y') }} {{ $themeSettings['hospital_name'] ?? config('app.name', 'Hospital') }}. All rights reserved.</span>
                        <span class="ml-4">Powered by <strong>{{ $themeSettings['system_name'] ?? 'DuncoHMS' }}</strong> © {{ $themeSettings['system_developer'] ?? 'Dunco Technologies' }}</span>
                    </div>
                    <a href="#top" class="hover:text-gray-700 ml-4" aria-label="Back to top">Back to top ↑</a>
                </div>
            </div>
        </footer>
    </body>
    </html>


