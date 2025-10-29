@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                <i class="fab fa-google text-blue-600 mr-3"></i>
                Google Calendar Integration
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Sync appointments and events with Google Calendar</p>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Google Calendar Configuration -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Google Calendar API Configuration</h3>
        
        <form action="#" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Client ID
                </label>
                <input type="text" name="client_id" value="{{ old('client_id') }}"
                    placeholder="Enter Google OAuth Client ID"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                @error('client_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Client Secret
                </label>
                <input type="password" name="client_secret" value="{{ old('client_secret') }}"
                    placeholder="Enter Google OAuth Client Secret"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                @error('client_secret')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Calendar ID
                </label>
                <input type="text" name="calendar_id" value="{{ old('calendar_id') }}"
                    placeholder="Enter default Calendar ID (or leave blank for primary)"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                @error('calendar_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="enabled" value="1"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable Google Calendar sync</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="auto_sync" value="1"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Automatically sync appointments to Google Calendar</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="sync_reminders" value="1" checked
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sync appointment reminders</span>
                </label>
            </div>

            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                <i class="fa fa-save mr-2"></i> Save Configuration
            </button>
        </form>
    </div>

    <!-- Sync Options -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                <i class="fa fa-sync text-blue-600 mr-2"></i> Sync Direction
            </h4>
            <div class="space-y-3">
                <label class="flex items-center">
                    <input type="radio" name="sync_direction" value="push" checked
                        class="text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Push to Google Calendar (One-way)</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="sync_direction" value="pull"
                        class="text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pull from Google Calendar</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="sync_direction" value="bidirectional"
                        class="text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Bidirectional Sync</span>
                </label>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                <i class="fa fa-calendar-check text-blue-600 mr-2"></i> Sync Events
            </h4>
            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                <li><i class="fa fa-check-circle text-blue-600 mr-2"></i> Appointments</li>
                <li><i class="fa fa-check-circle text-blue-600 mr-2"></i> Doctor schedules</li>
                <li><i class="fa fa-check-circle text-blue-600 mr-2"></i> Surgery schedules</li>
                <li><i class="fa fa-check-circle text-blue-600 mr-2"></i> Staff meetings</li>
                <li><i class="fa fa-check-circle text-blue-600 mr-2"></i> Equipment maintenance</li>
            </ul>
        </div>
    </div>

    <!-- Authorization Status -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Authorization Status</h4>
            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                <i class="fab fa-google mr-2"></i> Authorize with Google
            </button>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                <i class="fa fa-info-circle text-yellow-600 mr-2"></i>
                Not yet authorized. Click "Authorize with Google" to grant access to your Google Calendar.
            </p>
        </div>
    </div>

    <!-- Setup Instructions -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center">
            <i class="fa fa-info-circle text-blue-600 mr-2"></i> Setup Instructions
        </h3>
        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
            <p>To integrate Google Calendar:</p>
            <ol class="list-decimal list-inside ml-4 space-y-1">
                <li>Go to <a href="https://console.cloud.google.com/" target="_blank" class="text-blue-600 hover:underline">Google Cloud Console</a></li>
                <li>Create a new project or select an existing one</li>
                <li>Enable the Google Calendar API</li>
                <li>Create OAuth 2.0 credentials (Client ID and Client Secret)</li>
                <li>Add authorized redirect URI: <code class="bg-white dark:bg-gray-800 px-2 py-1 rounded">{{ url('/hms/integrations/google-calendar/callback') }}</code></li>
                <li>Enter the credentials above and click "Authorize with Google"</li>
            </ol>
            <p class="mt-3"><strong>Note:</strong> The integration will sync appointments and schedules between HMS and your Google Calendar.</p>
        </div>
    </div>
</div>
@endsection
