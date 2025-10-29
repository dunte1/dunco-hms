<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
          sidebarOpen: false, 
          darkMode: {{ $themeSettings['dark_mode'] ? 'true' : 'false' }}
      }"
      :class="{ 'dark': darkMode }"
      x-init="
          // Server setting is the source of truth for initial load
          // localStorage will be updated when user toggles dark mode
          var serverSetting = {{ $themeSettings['dark_mode'] ? 'true' : 'false' }};
          darkMode = serverSetting;
          $el.classList.toggle('dark', darkMode);
          
          // Clear any stale localStorage value that doesn't match server
          if (typeof(Storage) !== 'undefined') {
              localStorage.setItem('darkMode', String(serverSetting));
          }
      ">
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
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Sidebar Styles -->
        <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Sidebar JavaScript -->
        <script src="{{ asset('js/sidebar.js') }}"></script>
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            :root {
                --primary-color: {{ $themeSettings['primary_color'] ?? '#6366f1' }};
                --secondary-color: {{ $themeSettings['secondary_color'] ?? '#8b5cf6' }};
            }

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

            /* Logo styling */
            .navbar-brand img {
                max-height: 40px;
                width: auto;
            }

            .sidebar-brand img {
                max-height: 50px;
                width: auto;
            }

            /* Primary color overrides */
            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .btn-primary:hover {
                background-color: var(--secondary-color);
                border-color: var(--secondary-color);
            }

            .text-primary {
                color: var(--primary-color) !important;
            }

            .bg-primary {
                background-color: var(--primary-color) !important;
            }

            .border-primary {
                border-color: var(--primary-color) !important;
            }
            
            /* Enhanced Dark mode styles */
            .dark {
                color-scheme: dark;
                background-color: #1a1a1a !important;
                color: #ffffff !important;
            }
            
            .dark .bg-white {
                background-color: #2d2d2d !important;
            }
            
            .dark .bg-gray-50 {
                background-color: #1a1a1a !important;
            }
            
            .dark .bg-gray-100 {
                background-color: #2d2d2d !important;
            }
            
            .dark .bg-gray-800 {
                background-color: #1a1a1a !important;
            }
            
            .dark .bg-gray-900 {
                background-color: #1a1a1a !important;
            }
            
            .dark .text-gray-900 {
                color: #ffffff !important;
            }
            
            .dark .text-gray-800 {
                color: #ffffff !important;
            }
            
            .dark .text-gray-700 {
                color: #e0e0e0 !important;
            }
            
            .dark .text-gray-600 {
                color: #c0c0c0 !important;
            }
            
            .dark .text-gray-500 {
                color: #a0a0a0 !important;
            }
            
            .dark .text-gray-400 {
                color: #808080 !important;
            }
            
            .dark .text-gray-300 {
                color: #606060 !important;
            }
            
            .dark .text-gray-200 {
                color: #404040 !important;
            }
            
            .dark .text-gray-100 {
                color: #ffffff !important;
            }
            
            .dark .border-gray-200 {
                border-color: #404040 !important;
            }
            
            .dark .border-gray-700 {
                border-color: #404040 !important;
            }
            
            .dark .hover\:bg-gray-100:hover {
                background-color: #3d3d3d !important;
            }
            
            .dark .hover\:bg-gray-800:hover {
                background-color: #3d3d3d !important;
            }
            
            .dark .shadow-sm {
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5) !important;
            }
            
            .dark .card {
                background-color: #2d2d2d !important;
                border-color: #404040 !important;
                color: #ffffff !important;
            }
            
            .dark .form-control {
                background-color: #3d3d3d !important;
                border-color: #404040 !important;
                color: #ffffff !important;
            }
            
            .dark .form-control:focus {
                background-color: #3d3d3d !important;
                border-color: var(--primary-color) !important;
                color: #ffffff !important;
                box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25) !important;
            }
            
            .dark .btn-secondary {
                background-color: #6b7280 !important;
                border-color: #6b7280 !important;
                color: #ffffff !important;
            }
            
            .dark .btn-secondary:hover {
                background-color: #4b5563 !important;
                border-color: #4b5563 !important;
                color: #ffffff !important;
            }
            
            .dark .alert-info {
                background-color: #1e3a8a !important;
                border-color: #3b82f6 !important;
                color: #dbeafe !important;
            }
            
            .dark .alert-success {
                background-color: #166534 !important;
                border-color: #16a34a !important;
                color: #dcfce7 !important;
            }
            
            .dark .alert-danger {
                background-color: #991b1b !important;
                border-color: #dc2626 !important;
                color: #fecaca !important;
            }
            
            .dark .alert-warning {
                background-color: #92400e !important;
                border-color: #d97706 !important;
                color: #fef3c7 !important;
            }
            
            /* Sidebar specific dark mode styles */
            .dark .sidebar-container {
                background-color: #1f1f1f !important;
                color: #ffffff !important;
            }
            
            .dark .sidebar-header {
                background-color: #2d2d2d !important;
                border-color: #404040 !important;
            }
            
            .dark .nav-link {
                color: #e0e0e0 !important;
            }
            
            .dark .nav-link:hover {
                background-color: #3d3d3d !important;
                color: #ffffff !important;
            }
            
            .dark .nav-link.active {
                background-color: var(--primary-color) !important;
                color: #ffffff !important;
            }
            
            .dark .submenu-link {
                color: #c0c0c0 !important;
            }
            
            .dark .submenu-link:hover {
                background-color: #3d3d3d !important;
                color: #ffffff !important;
            }
            
            .dark .submenu-link.active {
                background-color: var(--primary-color) !important;
                color: #ffffff !important;
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
                
                <nav class="bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <!-- Mobile menu button -->
                                <button @click="sidebarOpen = !sidebarOpen" 
                                        class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <i class="fa fa-bars text-xl"></i>
                                </button>
                                
                                <!-- Brand/Logo -->
                                <div class="flex items-center ml-4 md:ml-0">
                                    @if($themeSettings['hospital_logo'] ?? false)
                                        <img src="{{ $themeSettings['hospital_logo'] }}" alt="Hospital Logo" class="me-3" style="max-height: 44px;">
                                    @endif
                                    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                        @yield('title', 'Dashboard')
                                    </h1>
                                </div>
                            </div>
                            
                            <!-- Right side of navbar -->
                            <div class="flex items-center space-x-4">
                                
                                <!-- Dark Mode Toggle -->
                                <button @click="darkMode = !darkMode" 
                                        class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800"
                                        title="Toggle Dark Mode">
                                    <i class="fa fa-moon" x-show="!darkMode"></i>
                                    <i class="fa fa-sun" x-show="darkMode"></i>
                                </button>
                                
                                <!-- User Dropdown -->
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white">
                                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <span class="hidden md:block">{{ auth()->user()->name }}</span>
                                        <i class="fa fa-chevron-down text-xs"></i>
                                    </button>
                                    
                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fa fa-user mr-2"></i> Profile
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <i class="fa fa-sign-out-alt mr-2"></i> Log Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main>
                    @yield('content')
                </main>
            </div>
        @endauth
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Dark Mode Persistence -->
        <script>
            // Initialize dark mode properly
            document.addEventListener('DOMContentLoaded', function() {
                const htmlEl = document.documentElement;
                const serverDarkMode = {{ $themeSettings['dark_mode'] ? 'true' : 'false' }};
                
                // Watch for dark mode toggle button clicks
                const toggleBtn = document.querySelector('[x-on\\:click*="darkMode"]');
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function() {
                        setTimeout(() => {
                            const alpineData = Alpine.$data(htmlEl);
                            if (alpineData && alpineData.darkMode !== undefined) {
                                const value = alpineData.darkMode;
                                if (typeof(Storage) !== 'undefined') {
                                    localStorage.setItem('darkMode', value);
                                    
                                    // Update server-side setting
                                    fetch('{{ route("hms.system.theme.update") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify({
                                            dark_mode: value,
                                            primary_color: '{{ $themeSettings["primary_color"] }}',
                                            secondary_color: '{{ $themeSettings["secondary_color"] }}'
                                        })
                                    }).catch(() => {
                                        // Silently fail if route doesn't exist
                                    });
                                }
                            }
                        }, 50);
                    });
                }
            });
            
            // Watch Alpine for dark mode changes
            document.addEventListener('alpine:init', () => {
                Alpine.effect(() => {
                    const htmlEl = document.documentElement;
                    setTimeout(() => {
                        if (Alpine.$data && Alpine.$data(htmlEl)) {
                            const data = Alpine.$data(htmlEl);
                            if (data.darkMode !== undefined) {
                                const originalWatch = data.$watch;
                                if (typeof originalWatch === 'function') {
                                    // Watch darkMode changes
                                    Alpine.effect(() => {
                                        const value = data.darkMode;
                                        if (typeof(Storage) !== 'undefined') {
                                            localStorage.setItem('darkMode', value);
                                        }
                                    });
                                }
                            }
                        }
                    }, 100);
                });
            });
        </script>
        
        @stack('scripts')
    </body>
    </html>


