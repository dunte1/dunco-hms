@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                <i class="fab fa-whatsapp text-green-600 mr-3"></i>
                WhatsApp API Integration
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Configure WhatsApp Business API for automated messaging</p>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- WhatsApp Configuration -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">WhatsApp Business API Configuration</h3>
        
        <form action="#" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        WhatsApp Business Account ID
                    </label>
                    <input type="text" name="account_id" value="{{ old('account_id') }}"
                        placeholder="Enter your WhatsApp Business Account ID"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                    @error('account_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        API Token
                    </label>
                    <input type="password" name="api_token" value="{{ old('api_token') }}"
                        placeholder="Enter your API token"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                    @error('api_token')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Phone Number ID
                    </label>
                    <input type="text" name="phone_number_id" value="{{ old('phone_number_id') }}"
                        placeholder="Enter your WhatsApp Business Phone Number ID"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                    @error('phone_number_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Webhook Verify Token
                    </label>
                    <input type="text" name="verify_token" value="{{ old('verify_token') }}"
                        placeholder="Enter webhook verify token"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                    @error('verify_token')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="enabled" value="1"
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable WhatsApp notifications</span>
                </label>
            </div>

            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                <i class="fa fa-save mr-2"></i> Save Configuration
            </button>
        </form>
    </div>

    <!-- Features -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                <i class="fa fa-paper-plane text-green-600 mr-2"></i> Automated Messages
            </h4>
            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                <li><i class="fa fa-check-circle text-green-600 mr-2"></i> Appointment reminders</li>
                <li><i class="fa fa-check-circle text-green-600 mr-2"></i> Prescription notifications</li>
                <li><i class="fa fa-check-circle text-green-600 mr-2"></i> Test result alerts</li>
                <li><i class="fa fa-check-circle text-green-600 mr-2"></i> Payment confirmations</li>
            </ul>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                <i class="fa fa-cogs text-green-600 mr-2"></i> Features
            </h4>
            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                <li><i class="fa fa-check-circle text-green-600 mr-2"></i> Two-way messaging</li>
                <li><i class="fa fa-check-circle text-green-600 mr-2"></i> Media attachments</li>
                <li><i class="fa fa-check-circle text-green-600 mr-2"></i> Template messages</li>
                <li><i class="fa fa-check-circle text-green-600 mr-2"></i> Delivery status tracking</li>
            </ul>
        </div>
    </div>

    <!-- Setup Instructions -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center">
            <i class="fa fa-info-circle text-blue-600 mr-2"></i> Setup Instructions
        </h3>
        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
            <p>To set up WhatsApp Business API:</p>
            <ol class="list-decimal list-inside ml-4 space-y-1">
                <li>Create a WhatsApp Business Account on Meta Business Suite</li>
                <li>Generate API credentials (Access Token, Phone Number ID)</li>
                <li>Configure webhook URL: <code class="bg-white dark:bg-gray-800 px-2 py-1 rounded">{{ url('/hms/integrations/whatsapp/webhook') }}</code></li>
                <li>Set up webhook verify token for security</li>
                <li>Test the connection with a sample message</li>
            </ol>
            <p class="mt-3"><strong>Note:</strong> WhatsApp Business API requires approval from Meta for production use.</p>
        </div>
    </div>
</div>
@endsection
