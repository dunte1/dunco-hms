<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $themeSettings['hospital_name'] ?? config('app.name', 'DuncoHMS') }}</title>

        <!-- Favicon -->
        @if($themeSettings['favicon'] ?? false)
            <link rel="icon" type="image/x-icon" href="{{ $themeSettings['favicon'] }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ $themeSettings['favicon'] }}">
        @else
            <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Sidebar Styles -->
        <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
        
        <!-- Sidebar Scripts -->
        <script src="{{ asset('js/sidebar.js') }}"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            .sidebar {
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.closed {
                transform: translateX(-100%);
            }
            @media (min-width: 768px) {
                .sidebar.closed {
                    transform: translateX(0);
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-800" 
          @toggle-sidebar.window="sidebarOpen = !sidebarOpen">
        
        @auth
            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 md:hidden"
                 @click="sidebarOpen = false">
            </div>

            <!-- Sidebar -->
            <aside class="fixed inset-y-0 left-0 z-50 w-64 sidebar"
                   :class="sidebarOpen ? '' : 'closed'"
                   x-transition:enter="transition-transform ease-in-out duration-300"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition-transform ease-in-out duration-300"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full">
                @include('partials.sidebar')
            </aside>

            <!-- Main Content -->
            <div class="md:ml-64">
                <!-- Top Navigation -->
                <nav class="bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <!-- Mobile menu button -->
                                <button @click="sidebarOpen = !sidebarOpen" 
                                        class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <i class="fa fa-bars text-xl"></i>
                                </button>
                                
                                <!-- Page Title -->
                                <div class="ml-4 md:ml-0">
                                    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                        @yield('title', 'Dashboard')
                                    </h1>
                                </div>
                            </div>
                            
                            <!-- Right side of navbar -->
                            <div class="flex items-center space-x-4">
                                <!-- User dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <span class="ml-2 text-gray-700 dark:text-gray-300 hidden md:block">
                                            {{ auth()->user()->name }}
                                        </span>
                                        <i class="fa fa-chevron-down ml-1 text-gray-400"></i>
                                    </button>
                                    
                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                                        <a href="{{ route('profile.edit') }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fa fa-user mr-2"></i> Profile
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <i class="fa fa-sign-out-alt mr-2"></i> Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="p-6">
                    @if(isset($header))
                        <div class="mb-6">
                            {{ $header }}
                        </div>
                    @elseif(hasSection('header'))
                        <div class="mb-6">
                            @yield('header')
                        </div>
                    @endif
                    
                    {{ $slot }}
                    @hasSection('content')
                        @yield('content')
                    @endif
                </main>
            </div>
        @else
            <!-- Guest Layout -->
            <div class="min-h-screen bg-gray-100">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot ?? '' }}
                    @yield('content')
                </main>
            </div>
        @endauth
        
        <!-- Sidebar JavaScript -->
        <script src="{{ asset('js/sidebar.js') }}"></script>
        
        <!-- Page-specific scripts -->
        @stack('scripts')
    </body>
</html>
