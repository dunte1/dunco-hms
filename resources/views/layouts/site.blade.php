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
            @php
                $faviconUrl = $themeSettings['favicon'];
                // Ensure proper URL format
                if (!str_starts_with($faviconUrl, 'http') && !str_starts_with($faviconUrl, '/')) {
                    $faviconUrl = asset('storage/' . $faviconUrl);
                } elseif (str_starts_with($faviconUrl, '/storage/')) {
                    $faviconUrl = asset($faviconUrl);
                }
            @endphp
            <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}">
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
                <div class="hidden md:block">{{ \App\Models\SystemSetting::get('header_open_hours', 'Mon–Fri 8:00–18:00') }}</div>
                <div class="flex items-center gap-4">
                    @php
                        $emergencyPhone = \App\Models\SystemSetting::get('header_emergency_phone', '+254 700 000 000');
                        $emergencyPhoneClean = preg_replace('/[^0-9+]/', '', $emergencyPhone);
                    @endphp
                    <a href="tel:{{ $emergencyPhoneClean }}" class="font-medium text-red-700">Emergency: {{ $emergencyPhone }}</a>
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
                        @php
                            $logoUrl = $themeSettings['hospital_logo'];
                            // Ensure proper URL format
                            if (!str_starts_with($logoUrl, 'http') && !str_starts_with($logoUrl, '/')) {
                                $logoUrl = asset('storage/' . $logoUrl);
                            } elseif (str_starts_with($logoUrl, '/storage/')) {
                                $logoUrl = asset($logoUrl);
                            }
                        @endphp
                        <img src="{{ $logoUrl }}" alt="{{ config('app.name', 'DuncoHMS') }} Logo" class="h-9 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="h-8 w-8 bg-indigo-600 rounded flex items-center justify-center" style="display: none;">
                            <span class="text-white font-bold text-lg">D</span>
                        </div>
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
                    @if(\App\Models\SystemSetting::get('footer_about_text'))
                        <p class="text-gray-600 mt-2">{{ \App\Models\SystemSetting::get('footer_about_text') }}</p>
                    @endif
                    <div class="flex gap-3 mt-3 text-gray-600">
                        @php
                            $socialFb = \App\Models\SystemSetting::get('footer_social_facebook', '');
                            $socialTw = \App\Models\SystemSetting::get('footer_social_twitter', '');
                            $socialIg = \App\Models\SystemSetting::get('footer_social_instagram', '');
                            $socialLi = \App\Models\SystemSetting::get('footer_social_linkedin', '');
                        @endphp
                        @if($socialFb)<a href="{{ $socialFb }}" target="_blank" aria-label="Facebook" class="hover:text-blue-600">FB</a>@endif
                        @if($socialTw)<a href="{{ $socialTw }}" target="_blank" aria-label="X" class="hover:text-gray-900">X</a>@endif
                        @if($socialIg)<a href="{{ $socialIg }}" target="_blank" aria-label="Instagram" class="hover:text-pink-600">IG</a>@endif
                        @if($socialLi)<a href="{{ $socialLi }}" target="_blank" aria-label="LinkedIn" class="hover:text-blue-700">IN</a>@endif
                    </div>
                </div>
                <div>
                    <div class="font-semibold mb-3">Departments</div>
                    <ul class="space-y-1 text-gray-700">
                        @php
                            $depts = json_decode(\App\Models\SystemSetting::get('footer_departments', '[]'), true);
                            if (!is_array($depts) || empty($depts)) {
                                $depts = [
                                    ['name' => 'Cardiology', 'link' => route('services') . '#cardiology'],
                                    ['name' => 'Radiology', 'link' => route('services') . '#radiology'],
                                    ['name' => 'Laboratory', 'link' => route('services') . '#lab'],
                                    ['name' => 'Pharmacy', 'link' => route('services') . '#pharmacy'],
                                ];
                            }
                        @endphp
                        @foreach($depts as $dept)
                            <li><a href="{{ $dept['link'] ?? '#' }}">{{ $dept['name'] ?? '' }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <div class="font-semibold mb-3">Patients</div>
                    <ul class="space-y-1 text-gray-700">
                        @php
                            $patientLinks = json_decode(\App\Models\SystemSetting::get('footer_patient_links', '[]'), true);
                            if (!is_array($patientLinks) || empty($patientLinks)) {
                                $patientLinks = [
                                    ['name' => 'Book Appointment', 'link' => route('book-appointment')],
                                    ['name' => 'Find a Doctor', 'link' => route('doctors')],
                                    ['name' => 'Contact & Directions', 'link' => route('contact')],
                                    ['name' => 'Our Features', 'link' => route('features')],
                                ];
                            }
                        @endphp
                        @foreach($patientLinks as $link)
                            <li><a href="{{ $link['link'] ?? '#' }}">{{ $link['name'] ?? '' }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <div class="font-semibold mb-3">Legal</div>
                    <ul class="space-y-1 text-gray-700">
                        @php
                            $legalLinks = json_decode(\App\Models\SystemSetting::get('footer_legal_links', '[]'), true);
                            if (!is_array($legalLinks) || empty($legalLinks)) {
                                $legalLinks = [
                                    ['name' => 'Terms of Service', 'link' => '#'],
                                    ['name' => 'Privacy Policy', 'link' => '#'],
                                ];
                            }
                        @endphp
                        @foreach($legalLinks as $link)
                            <li><a href="{{ $link['link'] ?? '#' }}">{{ $link['name'] ?? '' }}</a></li>
                        @endforeach
                    </ul>
                    @if(\App\Models\SystemSetting::get('footer_newsletter_enabled', '1'))
                        <form class="mt-4" aria-label="Newsletter subscribe">
                            <label class="block text-gray-700 mb-1">Subscribe</label>
                            <div class="flex gap-2">
                                <input type="email" class="border rounded p-2 flex-1" placeholder="you@example.com" aria-label="Email address">
                                <button class="px-3 py-2 bg-indigo-600 text-white rounded" type="button">Join</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            <div class="border-t">
                <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between text-xs text-gray-500">
                    <div class="text-center flex-1">
                        {{ \App\Models\SystemSetting::get('footer_copyright', '© ' . date('Y') . ' ' . config('app.name', 'Dunco Hospital') . '. All rights reserved.') }}
                        <span class="ml-4">Powered by <strong>{{ $themeSettings['system_name'] ?? 'DuncoHMS' }}</strong> © {{ $themeSettings['system_developer'] ?? 'Dunco Technologies' }}</span>
                    </div>
                    <a href="#top" class="hover:text-gray-700 ml-4" aria-label="Back to top">Back to top ↑</a>
                </div>
            </div>
        </footer>
    </body>
    </html>


