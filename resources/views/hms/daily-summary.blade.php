@extends('layouts.app')

@section('title', 'Daily Summary')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-calendar-day text-indigo-600 mr-3"></i> Daily Operations Summary
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Today's hospital operations overview — {{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Appointments Today</p>
                    <p class="text-3xl font-bold mt-2">{{ $summary['appointments'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-calendar-check text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">OPD Visits</p>
                    <p class="text-3xl font-bold mt-2">{{ $summary['opd_visits'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-stethoscope text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">IPD Admissions</p>
                    <p class="text-3xl font-bold mt-2">{{ $summary['ipd_admissions'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-bed text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Revenue Today</p>
                    <p class="text-3xl font-bold mt-2">${{ number_format($summary['revenue'] ?? 0, 0) }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-dollar-sign text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Appointments -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border">
            <div class="p-6 border-b dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-calendar mr-2 text-blue-600"></i> Recent Appointments
                </h2>
            </div>
            <div class="p-6 space-y-3">
                @forelse($summary['recent_appointments'] ?? [] as $apt)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $apt->patient->name ?? 'Walk-in' }}</p>
                            <p class="text-xs text-gray-500">{{ $apt->doctor->name ?? 'N/A' }} · {{ $apt->appointment_time ?? '' }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ ($apt->status ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($apt->status ?? 'scheduled') }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-400">
                        <i class="fa-solid fa-calendar text-3xl mb-2"></i>
                        <p class="text-sm">No appointments recorded today</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Department Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border">
            <div class="p-6 border-b dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-building mr-2 text-indigo-600"></i> Department Activity
                </h2>
            </div>
            <div class="p-6 space-y-4">
                @forelse($summary['department_activity'] ?? [] as $dept => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $dept }}</span>
                        <div class="flex items-center">
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min(($count / max($summary['department_activity'] ?? [1])) * 100, 100) }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $count }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-400">
                        <i class="fa-solid fa-building text-3xl mb-2"></i>
                        <p class="text-sm">No department activity data</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
