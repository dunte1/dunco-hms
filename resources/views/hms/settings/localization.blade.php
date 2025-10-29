@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
            <i class="fa fa-language text-indigo-600 mr-3"></i>
            Localization & Multi-Language Settings
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Configure language, date, time, and regional settings</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Localization Settings Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <form action="{{ route('hms.system.localization.update') }}" method="POST">
            @csrf

            <!-- Language Settings -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <i class="fa fa-globe text-indigo-600 mr-2"></i> Default Language
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($supportedLanguages as $code => $lang)
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition 
                            {{ $currentLocale === $code ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                            <input type="radio" name="default_locale" value="{{ $code }}" 
                                {{ $currentLocale === $code ? 'checked' : '' }}
                                class="mr-3 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-2xl mr-2">{{ $lang['flag'] }}</span>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $lang['name'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ strtoupper($code) }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                
                @error('default_locale')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date & Time Format -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <i class="fa fa-calendar-alt text-indigo-600 mr-2"></i> Date & Time Format
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date Format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Date Format <span class="text-red-500">*</span>
                        </label>
                        <select name="date_format" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Y-m-d" {{ $dateFormat === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2024-12-25)</option>
                            <option value="d/m/Y" {{ $dateFormat === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (25/12/2024)</option>
                            <option value="m/d/Y" {{ $dateFormat === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (12/25/2024)</option>
                            <option value="d-m-Y" {{ $dateFormat === 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY (25-12-2024)</option>
                            <option value="F j, Y" {{ $dateFormat === 'F j, Y' ? 'selected' : '' }}>Month Day, Year (December 25, 2024)</option>
                        </select>
                        @error('date_format')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Time Format <span class="text-red-500">*</span>
                        </label>
                        <select name="time_format" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="H:i" {{ $timeFormat === 'H:i' ? 'selected' : '' }}>24-Hour (14:30)</option>
                            <option value="h:i A" {{ $timeFormat === 'h:i A' ? 'selected' : '' }}>12-Hour (02:30 PM)</option>
                            <option value="H:i:s" {{ $timeFormat === 'H:i:s' ? 'selected' : '' }}>24-Hour with Seconds (14:30:45)</option>
                            <option value="h:i:s A" {{ $timeFormat === 'h:i:s A' ? 'selected' : '' }}>12-Hour with Seconds (02:30:45 PM)</option>
                        </select>
                        @error('time_format')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview -->
                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Preview:</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        Date: {{ now()->format($dateFormat) }} | Time: {{ now()->format($timeFormat) }}
                    </p>
                </div>
            </div>

            <!-- Timezone -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <i class="fa fa-clock text-indigo-600 mr-2"></i> Timezone
                </h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Default Timezone <span class="text-red-500">*</span>
                    </label>
                    <select name="timezone" required
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="UTC" {{ $timezone === 'UTC' ? 'selected' : '' }}>UTC</option>
                        <option value="Africa/Nairobi" {{ $timezone === 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi (EAT)</option>
                        <option value="Africa/Cairo" {{ $timezone === 'Africa/Cairo' ? 'selected' : '' }}>Africa/Cairo (EET)</option>
                        <option value="America/New_York" {{ $timezone === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                        <option value="Europe/London" {{ $timezone === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                        <option value="Asia/Dubai" {{ $timezone === 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                        <option value="Asia/Shanghai" {{ $timezone === 'Asia/Shanghai' ? 'selected' : '' }}>Asia/Shanghai (CST)</option>
                        <option value="Asia/Tokyo" {{ $timezone === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (JST)</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Current server time: {{ now($timezone)->format($dateFormat . ' ' . $timeFormat) }}
                    </p>
                    @error('timezone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex items-center justify-end gap-4">
                <a href="{{ route('hms.settings.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                    <i class="fa fa-times mr-2"></i> Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                    <i class="fa fa-save mr-2"></i> Save Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Language Switcher Info -->
    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center">
            <i class="fa fa-info-circle text-blue-600 mr-2"></i> Language Switcher
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Users can switch languages using the language selector in the top navigation bar. 
            The selected language will be saved in their session and applied across the entire system.
        </p>
    </div>
</div>
@endsection

