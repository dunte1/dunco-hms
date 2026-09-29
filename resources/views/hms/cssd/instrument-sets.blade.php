<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Instrument Sets</h1>
            <div class="mt-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instrumentSets as $set)
                        <tr>
                            <td class="px-6 py-4">{{ $set->name }}</td>
                            <td class="px-6 py-4">{{ $set->code }}</td>
                            <td class="px-6 py-4">{{ $set->instrument_count }}</td>
                            <td class="px-6 py-4">{{ $set->is_active ? 'Yes' : 'No' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No instrument sets found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $instrumentSets->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
