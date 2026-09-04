@extends('layouts.app')

@section('title', 'HR Dashboard')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-users-gear text-blue-600 mr-3"></i> HR Dashboard
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Human resources management overview</p>
        </div>
        <a href="{{ route('hms.hr.employees.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-user-plus mr-2"></i> Add Employee
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Employees</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats[0]['value'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Doctors</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats[1]['value'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-user-doctor text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Nurses</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats[2]['value'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-user-nurse text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">On Leave Today</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats[5]['value'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <i class="fa-solid fa-calendar-minus text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <a href="{{ route('hms.hr.employees.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 hover:shadow-md transition group">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg group-hover:bg-blue-200 transition">
                    <i class="fa-solid fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Employees</h3>
                    <p class="text-sm text-gray-500">Manage employee records</p>
                </div>
            </div>
        </a>

        <a href="{{ route('hms.hr.attendance.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 hover:shadow-md transition group">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg group-hover:bg-green-200 transition">
                    <i class="fa-solid fa-clock text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Attendance</h3>
                    <p class="text-sm text-gray-500">Track daily attendance</p>
                </div>
            </div>
        </a>

        <a href="{{ route('hms.hr.leave-requests.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 hover:shadow-md transition group">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg group-hover:bg-amber-200 transition">
                    <i class="fa-solid fa-calendar-days text-amber-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Leave Requests</h3>
                    <p class="text-sm text-gray-500">Manage leave applications</p>
                </div>
            </div>
        </a>

        <a href="{{ route('hms.hr.payrolls.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 hover:shadow-md transition group">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg group-hover:bg-green-200 transition">
                    <i class="fa-solid fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Payroll</h3>
                    <p class="text-sm text-gray-500">Process salaries</p>
                </div>
            </div>
        </a>

        <a href="{{ route('hms.hr.training-programs.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 hover:shadow-md transition group">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg group-hover:bg-purple-200 transition">
                    <i class="fa-solid fa-graduation-cap text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Training</h3>
                    <p class="text-sm text-gray-500">Training programs</p>
                </div>
            </div>
        </a>

        <a href="{{ route('hms.hr.announcements.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 hover:shadow-md transition group">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg group-hover:bg-red-200 transition">
                    <i class="fa-solid fa-bullhorn text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Announcements</h3>
                    <p class="text-sm text-gray-500">HR announcements</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Department Distribution & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border">
            <div class="p-6 border-b dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-chart-pie mr-2 text-blue-600"></i> Department Distribution
                </h2>
            </div>
            <div class="p-6">
                @forelse($deptChartLabels ?? [] as $index => $dept)
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $dept }}</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($deptChartData[$index] / max($deptChartData)) * 100 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $deptChartData[$index] ?? 0 }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fa-solid fa-chart-pie text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400">No department data available</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border">
            <div class="p-6 border-b dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-clock-rotate-left mr-2 text-green-600"></i> Recent Activity
                </h2>
            </div>
            <div class="p-6 space-y-4">
                @forelse($recentActivity ?? [] as $activity)
                    <div class="flex items-start">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg mr-3">
                            <i class="fa-solid fa-circle text-xs text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $activity['message'] ?? 'Activity logged' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $activity['time'] ?? '' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fa-solid fa-clock-rotate-left text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400">No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
