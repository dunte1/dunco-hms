<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-tools text-purple-600 mr-3"></i>
                        Instrument Trays
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track sterilization status of surgical instrument trays</p>
                </div>
                <button onclick="document.getElementById('addInstrumentModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> Add Instrument Tray
                </button>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Sterilized At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Expiry</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Last Used</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($instruments as $instrument)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $instrument->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $instrument->category ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $instrument->status === 'sterile' ? 'bg-green-100 text-green-800' : ($instrument->status === 'used' ? 'bg-blue-100 text-blue-800' : ($instrument->status === 'contaminated' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ ucfirst($instrument->status) }}
                                            @if($instrument->status === 'sterile' && $instrument->sterilization_expiry && $instrument->sterilization_expiry->isPast())
                                                <span class="text-red-600">(Expired)</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $instrument->sterilized_at ? $instrument->sterilized_at->format('M d, H:i') : '-' }}</td>
                                    <td class="px-6 py-4 text-sm {{ $instrument->sterilization_expiry && $instrument->sterilization_expiry->isPast() ? 'text-red-600 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">{{ $instrument->sterilization_expiry ? $instrument->sterilization_expiry->format('M d, H:i') : '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $instrument->lastUsedSchedule ? $instrument->lastUsedSchedule->schedule_number : '-' }}</td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        @if($instrument->status !== 'sterile' || ($instrument->sterilization_expiry && $instrument->sterilization_expiry->isPast()))
                                            <form action="{{ route('hms.ot.instrument-sterilize', $instrument) }}" method="POST" class="inline">
                                                @csrf
                                                <button class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs"><i class="fa fa-sterilize mr-1"></i> Sterilize</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i class="fa fa-tools text-5xl text-gray-400 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">No instrument trays found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($instruments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $instruments->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Instrument Modal -->
    <div id="addInstrumentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><i class="fa fa-plus text-purple-600 mr-2"></i>Add Instrument Tray</h3>
                <form action="{{ route('hms.ot.instrument-store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="e.g., General Surgery Tray, Orthopedic Set">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                        <input type="text" name="category" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="e.g., Surgical, Diagnostic, Ancillary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Description of instruments included..."></textarea>
                    </div>
                    <div class="flex gap-4 pt-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg"><i class="fa fa-save mr-2"></i>Save</button>
                        <button type="button" onclick="document.getElementById('addInstrumentModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
