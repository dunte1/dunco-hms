<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Nutrition Records</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Patient nutrition assessments and diet plans</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Total</p><p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Active</p><p class="text-2xl font-bold text-emerald-600">{{ $stats['active'] ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Completed</p><p class="text-2xl font-bold text-blue-600">{{ $stats['completed'] ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">High Risk</p><p class="text-2xl font-bold text-red-600">{{ $stats['high_risk'] ?? 0 }}</p></div>
            </div>

            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient or diet plan..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Filter</button>
            </form>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assessment</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">BMI</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Risk</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diet Plan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($records ?? []) as $record)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ optional($record->created_at)->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium">{{ $record->patient->full_name ?? ($record->patient->first_name ?? '—') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $record->assessment_type ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $record->bmi ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        @php($risk = $record->malnutrition_risk ?? 'none')
                                        <span class="px-2 py-1 text-xs rounded-full {{ $risk === 'high' ? 'bg-red-100 text-red-800' : ($risk === 'moderate' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">{{ ucfirst($risk) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm max-w-xs truncate">{{ $record->diet_plan ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full {{ ($record->status ?? 'active') === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800' }}">{{ $record->status ?? 'active' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-10 text-center text-gray-500">No nutrition records found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ ($records ?? collect())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
