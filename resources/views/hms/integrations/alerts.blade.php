@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                <i class="fa fa-bell-slash text-orange-600 mr-3"></i>
                Automated Alerts & Reminders
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Configure automated alerts and reminders for appointments, payments, and more</p>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Alert Configuration -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Alert Settings</h3>
        
        <form action="#" method="POST">
            @csrf
            
            <!-- Appointment Reminders -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    <i class="fa fa-calendar-alt text-blue-600 mr-2"></i> Appointment Reminders
                </h4>
                
                <div class="mb-4">
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="appointment_reminders" value="1" checked
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable appointment reminders</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Remind Before (Hours)
                        </label>
                        <input type="number" name="reminder_hours" value="24" min="1" max="168"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reminder Method
                        </label>
                        <select name="reminder_method" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="all">All Methods</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Payment Reminders -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    <i class="fa fa-money-bill-wave text-green-600 mr-2"></i> Payment Reminders
                </h4>
                
                <div class="mb-4">
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="payment_reminders" value="1" checked
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable payment reminders</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Remind After Due Date (Days)
                        </label>
                        <input type="number" name="payment_reminder_days" value="3" min="0" max="30"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Max Reminders
                        </label>
                        <input type="number" name="max_payment_reminders" value="3" min="1" max="10"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            <!-- Test Results Alerts -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    <i class="fa fa-flask text-purple-600 mr-2"></i> Test Results Alerts
                </h4>
                
                <div class="mb-4">
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="test_result_alerts" value="1" checked
                            class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Notify patients when test results are ready</span>
                    </label>
                </div>
            </div>

            <!-- Prescription Alerts -->
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    <i class="fa fa-prescription text-red-600 mr-2"></i> Prescription Alerts
                </h4>
                
                <div class="mb-4">
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="prescription_alerts" value="1"
                            class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable prescription pickup reminders</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                <i class="fa fa-save mr-2"></i> Save Settings
            </button>
        </form>
    </div>

    <!-- Alert Schedule -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Alert Schedule</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div>
                    <p class="font-medium text-gray-800 dark:text-gray-200">Appointment Reminders</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sent 24 hours before appointment</p>
                </div>
                <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded">Active</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div>
                    <p class="font-medium text-gray-800 dark:text-gray-200">Payment Reminders</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sent 3 days after due date</p>
                </div>
                <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded">Active</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div>
                    <p class="font-medium text-gray-800 dark:text-gray-200">Test Results</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sent when results are available</p>
                </div>
                <span class="px-3 py-1 text-xs bg-gray-100 text-gray-800 rounded">Inactive</span>
            </div>
        </div>
    </div>

    <!-- Information -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center">
            <i class="fa fa-info-circle text-blue-600 mr-2"></i> About Automated Alerts
        </h3>
        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
            <p>Automated alerts help improve patient communication and reduce no-shows by:</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>Sending timely appointment reminders</li>
                <li>Notifying patients about payment due dates</li>
                <li>Alerting when test results are ready</li>
                <li>Reminding about prescription pickups</li>
            </ul>
            <p class="mt-3"><strong>Note:</strong> SMS and WhatsApp alerts require proper integration setup. Email alerts work immediately.</p>
        </div>
    </div>
</div>
@endsection
