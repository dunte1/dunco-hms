<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Visitor Analytics</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Visitor traffic, check-ins, and department activity</p>
                </div>
                <a href="{{ route('hms.visitors.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">All Visitors</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Total Visitors</p>
                    <p class="text-2xl font-bold">{{ $dailyStats->sum('count') ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Days Tracked</p>
                    <p class="text-2xl font-bold">{{ $dailyStats->count() ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Avg Visit (min)</p>
                    <p class="text-2xl font-bold">{{ round($averageDuration ?? 0, 1) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Visit Types</p>
                    <p class="text-2xl font-bold">{{ $typeStats->count() ?? 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Daily Visits</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Visits</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Distribution</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse(($dailyStats ?? collect()) as $day)
                                    @php($max = max(1, $dailyStats->max('count')))
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $day->day ?? $day->date }}</td>
                                        <td class="px-4 py-2 text-sm font-medium">{{ $day->count }}</td>
                                        <td class="px-4 py-2 w-1/2">
                                            <div class="h-2 rounded bg-indigo-100 dark:bg-indigo-900/40 overflow-hidden">
                                                <div class="h-2 bg-indigo-500" style="width: {{ min(100, round(($day->count / $max) * 100)) }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">No visitor activity recorded</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Visit Types</h3>
                        </div>
                        <div class="p-4 space-y-2">
                            @forelse(($typeStats ?? collect()) as $type => $count)
                                @php($maxType = max(1, $typeStats->max()))
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-700 dark:text-gray-300">{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                                        <span class="font-medium">{{ $count }}</span>
                                    </div>
                                    <div class="h-2 rounded bg-rose-100 dark:bg-rose-900/40 overflow-hidden">
                                        <div class="h-2 bg-rose-500" style="width: {{ min(100, round(($count / $maxType) * 100)) }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No visit type data yet</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Department Activity</h3>
                        </div>
                        <div class="p-4 space-y-2">
                            @forelse(($departmentStats ?? collect()) as $dept => $count)
                                @php($maxDept = max(1, $departmentStats->max()))
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-700 dark:text-gray-300">{{ $dept }}</span>
                                        <span class="font-medium">{{ $count }}</span>
                                    </div>
                                    <div class="h-2 rounded bg-cyan-100 dark:bg-cyan-900/40 overflow-hidden">
                                        <div class="h-2 bg-cyan-500" style="width: {{ min(100, round(($count / $maxDept) * 100)) }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No department data yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
