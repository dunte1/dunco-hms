<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Ambulance Maintenance</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Fleet maintenance schedule</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($maintenance ?? collect()) as $row)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $row->ambulance->registration ?? $row->vehicle ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $row->maintenance_type ?? 'service' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ optional($row->due_date ?? $row->created_at)->format('M d, Y') }}</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">{{ $row->status ?? 'pending' }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No maintenance records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
