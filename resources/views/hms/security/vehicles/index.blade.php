<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Vehicle Access</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Hospital vehicle gate access log</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plate / Vehicle</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Direction</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($vehicles ?? $logs ?? collect()) as $row)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ optional($row->created_at)->format('M d, Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium">{{ $row->plate_number ?? $row->vehicle ?? $row->registration ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $row->driver_name ?? $row->driver ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $row->direction ?? $row->access_type ?? '—' }}</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $row->status ?? 'recorded' }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No vehicle access records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
