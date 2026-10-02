<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-book text-indigo-600 mr-3"></i> Journal Entries
                </h1>
                <a href="{{ route('journal.store') }}" class="hidden" aria-hidden="true"></a>
                <span class="text-sm text-gray-500">Use the finance journal form to post entries</span>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left">Entry #</th>
                                <th class="px-4 py-3 text-left">Date</th>
                                <th class="px-4 py-3 text-left">Description</th>
                                <th class="px-4 py-3 text-left">Reference</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Posted</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($entries as $entry)
                                <tr>
                                    <td class="px-4 py-3">{{ $entry->entry_number }}</td>
                                    <td class="px-4 py-3">{{ optional($entry->date)->format('d M Y') }}</td>
                                    <td class="px-4 py-3">{{ $entry->description }}</td>
                                    <td class="px-4 py-3">{{ $entry->reference_type }} {{ $entry->reference_id }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-xs {{ $entry->status==='posted' ? 'bg-green-100 text-green-800' : ($entry->status==='reversed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ $entry->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ optional($entry->posted_at)->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <a class="text-blue-600 hover:underline" href="{{ route('journal.show', $entry) }}">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No journal entries yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $entries->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
