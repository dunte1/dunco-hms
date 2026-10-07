<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Maintenance Requests</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Equipment maintenance work</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipment</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($requests ?? []) as $request)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $request->equipment->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $request->request_type ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $request->priority ?? '—' }}</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">{{ $request->status ?? 'pending' }}</span></td>
                                    <td class="px-4 py-3 text-sm">{{ optional($request->created_at)->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No maintenance requests</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($requests ?? null, 'links'))
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $requests->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
