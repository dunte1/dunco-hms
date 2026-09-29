@extends('layouts.app')

@section('title', 'Emergency Contacts Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Emergency Contacts Settings</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure emergency phone numbers displayed in the admin navbar and public site.</p>
    </div>

    @if(session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            <i class="fa fa-check-circle mr-2"></i>{{ session('status') }}
        </div>
    @endif

    <form action="{{ route('hms.settings.emergency-contacts.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('POST')

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fa fa-phone-alt text-red-500 mr-2"></i>Emergency Phone Numbers
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="emergency_phone_1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Primary Emergency Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="emergency_phone_1" id="emergency_phone_1" 
                           value="{{ old('emergency_phone_1', $contacts['emergency_phone_1']) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                           placeholder="+254 700 000 000" required>
                    <p class="mt-1 text-xs text-gray-500">Displayed in the admin navbar with red badge</p>
                </div>

                <div>
                    <label for="emergency_phone_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Secondary Emergency Phone
                    </label>
                    <input type="text" name="emergency_phone_2" id="emergency_phone_2" 
                           value="{{ old('emergency_phone_2', $contacts['emergency_phone_2']) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                           placeholder="+254 700 000 001">
                </div>

                <div>
                    <label for="ambulance_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Ambulance Dispatch Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ambulance_phone" id="ambulance_phone" 
                           value="{{ old('ambulance_phone', $contacts['ambulance_phone']) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                           placeholder="+254 700 000 002" required>
                    <p class="mt-1 text-xs text-gray-500">Displayed in the admin navbar with orange badge</p>
                </div>

                <div>
                    <label for="emergency_department_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Emergency Department Direct Line
                    </label>
                    <input type="text" name="emergency_department_phone" id="emergency_department_phone" 
                           value="{{ old('emergency_department_phone', $contacts['emergency_department_phone']) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                           placeholder="+254 700 000 003">
                </div>

                <div>
                    <label for="emergency_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Emergency Email
                    </label>
                    <input type="email" name="emergency_email" id="emergency_email" 
                           value="{{ old('emergency_email', $contacts['emergency_email']) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                           placeholder="emergency@duncohms.co.ke">
                </div>

                <div>
                    <label for="hospital_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Main Hospital Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="hospital_phone" id="hospital_phone" 
                           value="{{ old('hospital_phone', $contacts['hospital_phone']) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                           placeholder="+254 700 000 000" required>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" 
                    class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                <i class="fa fa-save mr-2"></i>Save Emergency Contacts
            </button>
        </div>
    </form>

    <div class="mt-8 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">
            <i class="fa fa-info-circle mr-1"></i>Where these contacts appear:
        </h3>
        <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
            <li>• <strong>Admin panel navbar</strong> — Emergency and Ambulance buttons (top right)</li>
            <li>• <strong>Public site header</strong> — Emergency phone link</li>
            <li>• <strong>Public site footer</strong> — Hospital phone number</li>
            <li>• <strong>Patient portal</strong> — Emergency contact information</li>
        </ul>
    </div>
</div>
@endsection
