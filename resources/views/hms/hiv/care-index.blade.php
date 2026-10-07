<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">HIV Care</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">HIV care enrollments and ART follow-up</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enrolled</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Regimen</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($enrollments ?? $care ?? collect()) as $row)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $row->patient->full_name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ optional($row->enrolled_at ?? $row->created_at)->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $row->regimen ?? '—' }}</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">{{ $row->status ?? 'active' }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No HIV care enrollments yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
