@extends('layouts.app')

@section('title', 'Theme Preview')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-palette text-pink-600 mr-3"></i> Theme Preview
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Preview your current theme settings</p>
        </div>
        <a href="{{ route('hms.settings.theme') }}" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-edit mr-2"></i> Customize Theme
        </a>
    </div>

    <!-- Theme Colors Preview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Primary Color</h3>
            <div class="h-24 rounded-lg" style="background-color: {{ $themeSettings['primary_color'] ?? '#10b981' }}"></div>
            <p class="mt-2 text-sm text-gray-500 font-mono">{{ $themeSettings['primary_color'] ?? '#10b981' }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Secondary Color</h3>
            <div class="h-24 rounded-lg" style="background-color: {{ $themeSettings['secondary_color'] ?? '#3b82f6' }}"></div>
            <p class="mt-2 text-sm text-gray-500 font-mono">{{ $themeSettings['secondary_color'] ?? '#3b82f6' }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Accent Color</h3>
            <div class="h-24 rounded-lg" style="background-color: {{ $themeSettings['accent_color'] ?? '#f59e0b' }}"></div>
            <p class="mt-2 text-sm text-gray-500 font-mono">{{ $themeSettings['accent_color'] ?? '#f59e0b' }}</p>
        </div>
    </div>

    <!-- Layout Preview -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Settings Overview</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-xs text-gray-500 uppercase font-semibold">Font Family</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $themeSettings['font_family'] ?? 'Figtree' }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-xs text-gray-500 uppercase font-semibold">Dark Mode</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ ($themeSettings['dark_mode'] ?? false) ? 'Enabled' : 'Disabled' }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-xs text-gray-500 uppercase font-semibold">Sidebar Style</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ ucfirst($themeSettings['sidebar_style'] ?? 'Default') }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-xs text-gray-500 uppercase font-semibold">Hospital Name</p>
                <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $themeSettings['hospital_name'] ?? config('app.name') }}</p>
            </div>
        </div>
    </div>

    <!-- Component Preview -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Component Preview</h3>
        <div class="space-y-4">
            <div class="flex gap-3">
                <button class="px-4 py-2 rounded-lg text-white font-semibold" style="background-color: {{ $themeSettings['primary_color'] ?? '#10b981' }}">Primary Button</button>
                <button class="px-4 py-2 rounded-lg text-white font-semibold" style="background-color: {{ $themeSettings['secondary_color'] ?? '#3b82f6' }}">Secondary Button</button>
                <button class="px-4 py-2 rounded-lg text-white font-semibold" style="background-color: {{ $themeSettings['accent_color'] ?? '#f59e0b' }}">Accent Button</button>
            </div>
            <div class="p-4 rounded-lg border-2" style="border-color: {{ $themeSettings['primary_color'] ?? '#10b981' }}">
                <p class="text-sm text-gray-700 dark:text-gray-300">This is a preview card with primary border color</p>
            </div>
        </div>
    </div>
</div>
@endsection
