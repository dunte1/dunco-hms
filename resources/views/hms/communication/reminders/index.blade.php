<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-bell text-yellow-600 mr-3"></i>
                    Automated Reminders
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage appointment and payment reminders</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Upcoming Appointments</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($upcomingAppointments) }}</p>
                            <p class="text-sm opacity-90 mt-2">Next 7 days</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar-check text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Pending Payments</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($pendingPayments) }}</p>
                            <p class="text-sm opacity-90 mt-2">Require reminder</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-credit-card text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <a href="{{ route('hms.reminders.appointments') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <h3 class="text-lg font-bold">Appointment Reminders</h3>
                                <p class="text-sm opacity-90 mt-1">Send to patients</p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                                <i class="fa fa-calendar-alt text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Remind patients about upcoming appointments</p>
                    </div>
                </a>

                <a href="{{ route('hms.reminders.payments') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <h3 class="text-lg font-bold">Payment Reminders</h3>
                                <p class="text-sm opacity-90 mt-1">Send to patients</p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                                <i class="fa fa-dollar-sign text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Remind patients about pending payments</p>
                    </div>
                </a>
            </div>

            <!-- Reminder Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-4">
                    <h3 class="text-lg font-bold text-white">Reminder Settings</h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Appointment Reminders -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-calendar text-blue-600 mr-2"></i>
                            Appointment Reminders
                        </h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" checked class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Send reminder 24 hours before appointment</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Send reminder 3 hours before appointment</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" checked class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Send via Email</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" checked class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Send via SMS</span>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Reminders -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-money-bill text-green-600 mr-2"></i>
                            Payment Reminders
                        </h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" checked class="rounded text-green-600 focus:ring-green-500">
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Send reminder on due date</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" checked class="rounded text-green-600 focus:ring-green-500">
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Send reminder 3 days after due date</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-green-600 focus:ring-green-500">
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Send reminder weekly until paid</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" checked class="rounded text-green-600 focus:ring-green-500">
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Send via Email</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-save mr-2"></i> Save Settings
                        </button>
                        <button type="button" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-paper-plane mr-2"></i> Send All Reminders Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

