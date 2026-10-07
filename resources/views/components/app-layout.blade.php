<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          sidebarOpen: false,
          sidebarCollapsed: localStorage.getItem('sidebar-collapsed') === 'true',
          darkMode: {{ !empty($themeSettings['dark_mode']) ? 'true' : 'false' }}
      }"
      :class="{ 'dark': darkMode }"
      x-init="
          document.documentElement.classList.toggle('dark', darkMode);
          $watch('sidebarCollapsed', val => localStorage.setItem('sidebar-collapsed', val));
          $watch('darkMode', val => {
              document.documentElement.classList.toggle('dark', val);
              localStorage.setItem('darkMode', val);
              fetch('{{ route('hms.system.theme.update') }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content
                  },
                  body: JSON.stringify({
                      dark_mode: val,
                      primary_color: '{{ $themeSettings['primary_color'] ?? '#000075' }}',
                      secondary_color: '{{ $themeSettings['secondary_color'] ?? '#00001A' }}'
                  })
              }).catch(function() {});
          })
      ">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $themeSettings['hospital_name'] ?? config('app.name', 'DuncoHMS') }}</title>

        <!-- PWA -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="{{ $themeSettings['primary_color'] ?? '#000075' }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="{{ $themeSettings['hospital_name'] ?? 'Dunco HMS' }}">
        <link rel="apple-touch-icon" href="{{ asset('images/pwa/icon-152x152.png') }}">

        <!-- Favicon -->
        @if(!empty($themeSettings['favicon']))
            <link rel="icon" type="image/x-icon" href="{{ $themeSettings['favicon'] }}">
        @else
            <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="{{ asset('js/sidebar.js') }}"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary: {{ $themeSettings['primary_color'] ?? '#000075' }};
                --secondary: {{ $themeSettings['secondary_color'] ?? '#00001A' }};
                --accent: {{ $themeSettings['accent_color'] ?? '#2563EB' }};
                --sidebar-bg: {{ $themeSettings['sidebar_bg'] ?? '#00001A' }};
                --sidebar-text: {{ $themeSettings['sidebar_text'] ?? 'rgba(255,255,255,0.75)' }};
                --sidebar-muted: {{ $themeSettings['sidebar_muted'] ?? 'rgba(255,255,255,0.5)' }};
                --sidebar-hover: {{ $themeSettings['sidebar_hover'] ?? 'rgba(255,255,255,0.09)' }};
                --main-bg: {{ $themeSettings['main_bg'] ?? '#F7F9FA' }};
            }
            .sidebar { transition: width 0.3s ease-in-out, transform 0.3s ease-in-out; }
            .sidebar.closed { transform: translateX(-100%); }
            @media (min-width: 768px) { .sidebar.closed { transform: translateX(0); } }
            .sidebar.collapsed { width: 72px !important; max-width: 72px !important; }
            .sidebar.collapsed .sidebar-container { width: 72px !important; max-width: 72px !important; overflow: hidden; transition: width 0.3s ease-in-out; }
            .sidebar.collapsed .sidebar-header-text,
            .sidebar.collapsed .menu-item span,
            .sidebar.collapsed .nested-menu-item span,
            .sidebar.collapsed .submenu-link span,
            .sidebar.collapsed .nested-link span,
            .sidebar.collapsed .menu-item .fa-chevron-down,
            .sidebar.collapsed .nested-menu-item .fa-chevron-down,
            .sidebar.collapsed .submenu-link .badge,
            .sidebar.collapsed .sidebar-nav .submenu,
            .sidebar.collapsed .sidebar-nav .nested-submenu { display: none !important; }
            .sidebar.collapsed .menu-item { justify-content: center; padding: 0.75rem; }
            .sidebar.collapsed .menu-icon { margin-right: 0; }
            .sidebar.collapsed .submenu-link { justify-content: center; padding: 0.5rem; }
            .sidebar.collapsed .nested-link { justify-content: center; padding: 0.375rem; }
            .sidebar.collapsed .sidebar-header { padding: 1rem !important; }
            .sidebar.collapsed .sidebar-header .hospital-name,
            .sidebar.collapsed .sidebar-header .hospital-subtitle { display: none; }
            .sidebar.collapsed .sidebar-header .toggle-btn { margin: 0; }
            .sidebar.collapsed .sidebar-header .logo-wrapper { margin: 0; }
            .sidebar.collapsed .sidebar-footer-content span,
            .sidebar.collapsed .sidebar-footer-version { display: none; }
            .main-content { transition: margin-left 0.3s ease-in-out; }
            @media (max-width: 767px) {
                .sidebar { width: 280px !important; }
                .sidebar.collapsed { width: 280px !important; }
                .sidebar.collapsed .sidebar-container { width: 280px !important; }
                .sidebar.collapsed .sidebar-header-text,
                .sidebar.collapsed .menu-item span,
                .sidebar.collapsed .nested-menu-item span,
                .sidebar.collapsed .submenu-link span,
                .sidebar.collapsed .nested-link span,
                .sidebar.collapsed .menu-item .fa-chevron-down,
                .sidebar.collapsed .nested-menu-item .fa-chevron-down,
                .sidebar.collapsed .submenu-link .badge,
                .sidebar.collapsed .sidebar-nav .submenu,
                .sidebar.collapsed .sidebar-nav .nested-submenu { display: revert !important; }
                .sidebar.collapsed .menu-item { justify-content: space-between; padding: 0.75rem 1rem; }
                .sidebar.collapsed .menu-icon { margin-right: 0.75rem; }
                .sidebar.collapsed .submenu-link { justify-content: flex-start; padding: 0.5rem 0.75rem; }
                .sidebar.collapsed .nested-link { justify-content: flex-start; padding: 0.375rem 1rem; }
                .sidebar.collapsed .sidebar-header { padding: 1.5rem !important; }
                .sidebar.collapsed .sidebar-header .hospital-name,
                .sidebar.collapsed .sidebar-header .hospital-subtitle { display: revert; }
                .sidebar.collapsed .sidebar-header .toggle-btn { margin-left: auto; }
                .sidebar.collapsed .sidebar-header .logo-wrapper { margin-right: 0.75rem; }
            }
        </style>
    </head>
        <body class="font-sans antialiased"
              :class="darkMode ? 'bg-gray-900' : 'bg-gray-50'"
              :style="darkMode ? '' : 'background-color: {{ $themeSettings['main_bg'] ?? '#F7F9FA' }}'"
              @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
              @toggle-sidebar-collapse.window="sidebarCollapsed = !sidebarCollapsed">

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
            <aside class="fixed inset-y-0 left-0 z-50 sidebar"
                   :class="(sidebarOpen ? '' : 'closed') + (sidebarCollapsed ? ' collapsed' : '')">
                @include('partials.sidebar')
            </aside>

            <!-- Main Content -->
            <div class="main-content md:ml-64" :style="sidebarCollapsed ? 'margin-left: 72px' : ''">
                <!-- Top Navigation -->
                <nav class="bg-white dark:bg-gray-900 shadow-sm" style="border-bottom: 1px solid {{ $themeSettings['navbar_border'] ?? '#E5ECEB' }};">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <i class="fa fa-bars text-xl"></i>
                                </button>
                                <button x-show="sidebarCollapsed" @click="sidebarCollapsed = false" class="hidden md:flex p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" title="Expand sidebar">
                                    <i class="fa fa-bars text-lg"></i>
                                </button>
                                <div class="ml-2 md:ml-2">
                                    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $title ?? 'Dashboard' }}</h1>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <button @click="darkMode = !darkMode"
                                        class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800"
                                        title="Toggle Dark Mode">
                                    <i class="fa fa-moon" x-show="!darkMode"></i>
                                    <i class="fa fa-sun" x-show="darkMode"></i>
                                </button>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-2 rounded-full text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 relative"><i class="fa fa-bell text-lg"></i></button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                                        <p class="px-4 py-6 text-sm text-gray-500 text-center">No new notifications</p>
                                    </div>
                                </div>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center"><span class="text-white font-medium text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span></div>
                                        <span class="ml-2 text-gray-700 dark:text-gray-300 hidden md:block">{{ auth()->user()->name }}</span>
                                        <i class="fa fa-chevron-down ml-1 text-gray-400"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                                        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</p>
                                        </div>
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><i class="fa fa-user mr-2"></i> Profile</a>
                                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><i class="fa fa-sign-out-alt mr-2"></i> Logout</button></form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="p-4 md:p-6">
                    <x-flash />
                    {{ $slot ?? '' }}
                    {{ $content ?? '' }}
                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="bg-white dark:bg-gray-900 mt-8" style="border-top: 1px solid {{ $themeSettings['navbar_border'] ?? '#E5ECEB' }};">
                    <div class="px-6 py-3">
                        <div class="flex flex-wrap items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>&copy; {{ date('Y') }} {{ $themeSettings['hospital_name'] ?? config('app.name', 'Dunco HMS') }}. All rights reserved.</span>
                            <span>Powered by <a href="https://duncowebsolutions.co.ke" target="_blank" class="font-semibold text-blue-600 hover:text-blue-800">Dunco Web Solutions</a></span>
                        </div>
                    </div>
                </footer>
            </div>
        @else
            {{ $slot ?? '' }}
            @yield('content')
        @endauth

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js?v=2').then(function(registration) {
                        if (registration.waiting) {
                            registration.waiting.postMessage({ type: 'skip-waiting' });
                        }
                    }).catch(function() {});
                });
            }
        </script>
        @stack('scripts')
    </body>
</html>
