<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'DuncoHMS') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <header class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-xl font-semibold">{{ config('app.name', 'DuncoHMS') }}</a>
                <nav class="space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900">Home</a>
                    <a href="{{ route('services') }}" class="text-gray-700 hover:text-gray-900">Services</a>
                    <a href="{{ route('doctors') }}" class="text-gray-700 hover:text-gray-900">Doctors</a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-gray-900">About</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-gray-900">Contact</a>
                    <a href="{{ route('features') }}" class="text-gray-700 hover:text-gray-900">Our Features</a>
                    <a href="{{ route('book-appointment') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Book Appointment</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    @endauth
                </nav>
                <div>
                    <div class="inline-flex items-center space-x-2">
                        @foreach(['ar','de','en','es','fr','it','pt','ru','tr','zh'] as $loc)
                            <a class="text-sm text-gray-500 hover:text-gray-700" href="{{ route('lang.switch', $loc) }}">{{ strtoupper($loc) }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="text-sm text-gray-500 text-center">
                    <p>&copy; {{ date('Y') }} {{ $themeSettings['hospital_name'] ?? config('app.name', 'Dunco Hospital') }}. All rights reserved.</p>
                    <p class="mt-2 text-xs">Powered by <strong>{{ $themeSettings['system_name'] ?? 'DuncoHMS' }}</strong> &copy; {{ date('Y') }} {{ $themeSettings['system_developer'] ?? 'Dunco Technologies' }}</p>
                </div>
            </div>
        </footer>
    </body>
    </html>


