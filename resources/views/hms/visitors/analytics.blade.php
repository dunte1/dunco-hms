<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-chart-bar text-purple-600 mr-3"></i>
                        Visitor Analytics
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Visitor statistics and insights</p>
                </div>
                <a href="{{ route('hms.visitors.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fa fa-arrow-left mr-2"></i> Back
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Visitors (30 days)</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $dailyStats->sum('count') }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                            <i class="fa fa-users text-purple-600 dark:text-purple-300 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Visit Duration</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                {{ $averageDuration ? round($averageDuration) : 0 }}<span class="text-lg">min</span>
                            </p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                            <i class="fa fa-clock text-blue-600 dark:text-blue-300 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Most Common Type</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white mt-2">
                                {{ $typeStats->keys()->first() ? ucfirst($typeStats->keys()->first()) : 'N/A' }}
                            </p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                            <i class="fa fa-chart-pie text-green-600 dark:text-green-300 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Top Department</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white mt-2">
                                {{ $departmentStats->keys()->first() ? Str::limit($departmentStats->keys()->first(), 15) : 'N/A' }}
                            </p>
                        </div>
                        <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                            <i class="fa fa-building text-orange-600 dark:text-orange-300 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Daily Visitor Trends -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fa fa-chart-line text-purple-600 mr-2"></i> Daily Visitor Trends (Last 30 Days)
                    </h3>
                    <div class="h-64 flex items-end justify-between gap-2">
                        @foreach($dailyStats as $stat)
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-purple-500 rounded-t hover:bg-purple-600 transition" 
                                 style="height: {{ ($stat->count / max($dailyStats->max('count'), 1)) * 100 }}%"
                                 title="{{ $stat->date }}: {{ $stat->count }} visitors">
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ \Carbon\Carbon::parse($stat->date)->format('M d') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Visitor Type Distribution -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fa fa-chart-pie text-blue-600 mr-2"></i> Visitor Type Distribution
                    </h3>
                    <div class="space-y-4">
                        @foreach($typeStats as $type => $count)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($type) }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $count }} visitors</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ ($count / max($typeStats->sum(), 1)) * 100 }}%">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Department Statistics -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fa fa-building text-orange-600 mr-2"></i> Department Visitor Statistics
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Visitor Count</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Percentage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Visual</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @php
                                    $totalDeptVisitors = $departmentStats->sum();
                                @endphp
                                @foreach($departmentStats->sortDesc() as $dept => $count)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $dept }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $totalDeptVisitors > 0 ? round(($count / $totalDeptVisitors) * 100, 1) : 0 }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-orange-600 h-2 rounded-full" 
                                                 style="width: {{ $totalDeptVisitors > 0 ? ($count / $totalDeptVisitors) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

