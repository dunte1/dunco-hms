<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center"><i class="fa fa-skull-crossbones text-gray-600 mr-3"></i>Mortuary Management</h1>
                </div>
                <button onclick="document.getElementById('addBodyModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition"><i class="fa fa-plus mr-2"></i> Register Body</button>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Body ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Received</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Storage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cause of Death</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($records as $record)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $record->body_id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $record->received_at?->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $record->storage_location ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ $record->cause_of_death ?? '-' }}</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $record->status === 'stored' ? 'bg-blue-100 text-blue-800' : ($record->status === 'released' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">{{ ucfirst($record->status) }}</span></td>
                                <td class="px-6 py-4 text-right"><a href="{{ route('hms.mortuary.show', $record) }}" class="px-2 py-1 bg-blue-600 text-white rounded text-xs"><i class="fa fa-eye"></i></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No records in mortuary</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Add Body Modal -->
    <div id="addBodyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Register Body</h3>
            <form action="{{ route('hms.mortuary.store') }}" method="POST" class="space-y-4">
                @csrf
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Body ID *</label><input type="text" name="body_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="e.g., MORT-001"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Storage Location</label><input type="text" name="storage_location" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="e.g., Cabinet A-3"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cause of Death</label><textarea name="cause_of_death" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Family Contact</label><input type="text" name="family_contact_name" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label><input type="text" name="family_contact_phone" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Save</button>
                    <button type="button" onclick="document.getElementById('addBodyModal').classList.add('hidden')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
