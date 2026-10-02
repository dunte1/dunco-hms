<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-list-alt text-blue-600 mr-3"></i> Radiology Modality Worklist
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Studies awaiting imaging / reporting</p>
            </div>

            @php
                $items = $items ?? $worklist ?? collect();
            @endphp

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left">Request</th>
                                <th class="px-4 py-3 text-left">Patient</th>
                                <th class="px-4 py-3 text-left">Modality</th>
                                <th class="px-4 py-3 text-left">Priority</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Scheduled</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($items ?? collect()) as $item)
                                <tr>
                                    <td class="px-4 py-3">#{{ optional($item->radiologyRequest)->request_number ?? $item->id }}</td>
                                    <td class="px-4 py-3">{{ optional(optional($item->radiologyRequest)->patient)->full_name ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $item->modality }}</td>
                                    <td class="px-4 py-3">{{ $item->priority }}</td>
                                    <td class="px-4 py-3">{{ $item->status }}</td>
                                    <td class="px-4 py-3">{{ optional($item->scheduled_time)->format('d M H:i') }}</td>
                                    <td class="px-4 py-3">
                                        @if($item->status !== 'completed')
                                            <form method="POST" action="{{ route('hms.radiology.worklist.complete', $item) }}" class="inline">
                                                @csrf
                                                <button class="text-green-600 hover:underline text-xs">Complete</button>
                                            </form>
                                        @else
                                            <span class="text-green-600 text-xs">Done</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Worklist is empty.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
