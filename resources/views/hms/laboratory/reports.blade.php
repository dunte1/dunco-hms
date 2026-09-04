@extends('layouts.app')

@section('title', 'Lab Reports')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-chart-bar text-cyan-600 mr-3"></i> Laboratory Reports
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Laboratory analytics and report generation</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-cyan-100 dark:bg-cyan-900 rounded-lg">
                    <i class="fa-solid fa-flask text-cyan-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Tests</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats[0]['value'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg">
                    <i class="fa-solid fa-clock text-amber-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats[1]['value'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Completed Today</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats[2]['value'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fa-solid fa-user-nurse text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Technicians</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats[3]['value'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Requests -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border">
            <div class="p-6 border-b dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-clock-rotate-left mr-2 text-cyan-600"></i> Recent Lab Requests
                </h2>
            </div>
            <div class="p-6 space-y-3">
                @forelse($recentRequests ?? [] as $request)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $request->patient->first_name ?? 'N/A' }} {{ $request->patient->last_name ?? '' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->doctor->name ?? 'N/A' }} · {{ $request->request_date?->format('M d') ?? '' }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' : ($request->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ ucfirst($request->status ?? 'pending') }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-400">
                        <i class="fa-solid fa-flask text-3xl mb-2"></i>
                        <p class="text-sm">No recent requests</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Popular Tests -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border">
            <div class="p-6 border-b dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-trophy mr-2 text-amber-600"></i> Most Requested Tests
                </h2>
            </div>
            <div class="p-6 space-y-3">
                @forelse($popularTests ?? [] as $index => $test)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-lg font-bold text-cyan-600 mr-3">#{{ $index + 1 }}</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $test->name ?? 'Unknown Test' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $test->category->name ?? 'General' }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $test->request_items_count ?? 0 }} requests</span>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-400">
                        <i class="fa-solid fa-trophy text-3xl mb-2"></i>
                        <p class="text-sm">No test data available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
