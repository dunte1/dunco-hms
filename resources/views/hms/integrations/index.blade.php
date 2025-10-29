@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                <i class="fa fa-plug text-cyan-600 mr-3"></i>
                Integrations
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage third-party integrations and services</p>
        </div>
    </div>

    <!-- Integration Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Payment Gateways -->
        <a href="{{ route('hms.integrations.payment-gateways') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition border-2 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fa fa-money-bill-wave text-3xl text-green-600 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Payment Gateways</h3>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Configure M-Pesa, Stripe, and PayPal integrations</p>
            <div class="flex items-center text-green-600 text-sm font-medium">
                Configure <i class="fa fa-arrow-right ml-2"></i>
            </div>
        </a>

        <!-- WhatsApp -->
        <a href="{{ route('hms.integrations.whatsapp') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition border-2 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fab fa-whatsapp text-3xl text-green-600 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">WhatsApp API</h3>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Set up WhatsApp Business API for automated messaging</p>
            <div class="flex items-center text-green-600 text-sm font-medium">
                Configure <i class="fa fa-arrow-right ml-2"></i>
            </div>
        </a>

        <!-- Google Calendar -->
        <a href="{{ route('hms.integrations.google-calendar') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition border-2 border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fab fa-google text-3xl text-blue-600 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Google Calendar</h3>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Sync appointments with Google Calendar</p>
            <div class="flex items-center text-blue-600 text-sm font-medium">
                Configure <i class="fa fa-arrow-right ml-2"></i>
            </div>
        </a>

        <!-- Automated Alerts -->
        <a href="{{ route('hms.integrations.alerts') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition border-2 border-orange-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fa fa-bell-slash text-3xl text-orange-600 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Automated Alerts</h3>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Configure automated alerts and reminders</p>
            <div class="flex items-center text-orange-600 text-sm font-medium">
                Configure <i class="fa fa-arrow-right ml-2"></i>
            </div>
        </a>

        <!-- Data Sync -->
        <a href="{{ route('hms.integrations.data-sync') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition border-2 border-indigo-500">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fa fa-sync-alt text-3xl text-indigo-600 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Data Sync & Backup</h3>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Schedule backups and data synchronization</p>
            <div class="flex items-center text-indigo-600 text-sm font-medium">
                Configure <i class="fa fa-arrow-right ml-2"></i>
            </div>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fa fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Active</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">2</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <i class="fa fa-exclamation-circle text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">3</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
