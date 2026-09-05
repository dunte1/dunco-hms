<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-sterilize text-cyan-600 mr-3"></i>CSSD - Central Sterile Services
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Sterilization tracking and instrument management</p>
                </div>
                <button onclick="document.getElementById('addInstrumentModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> Add Instrument
                </button>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <!-- Tabs -->
            <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex">
                        <button onclick="document.getElementById('instruments-tab').classList.remove('hidden');document.getElementById('batches-tab').classList.add('hidden')" class="px-6 py-3 text-sm font-medium text-cyan-600 border-b-2 border-cyan-600">Instruments</button>
                        <button onclick="document.getElementById('batches-tab').classList.remove('hidden');document.getElementById('instruments-tab').classList.add('hidden')" class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700">Sterilization Batches</button>
                    </nav>
                </div>
            </div>

            <!-- Instruments Tab -->
            <div id="instruments-tab">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Sterilized</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($instruments as $instrument)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $instrument->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $instrument->category ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $instrument->quantity }}</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $instrument->status === 'available' ? 'bg-green-100 text-green-800' : ($instrument->status === 'in_use' ? 'bg-blue-100 text-blue-800' : ($instrument->status === 'sterilizing' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">{{ ucfirst(str_replace('_', ' ', $instrument->status)) }}</span></td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $instrument->location ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $instrument->last_sterilized_at?->format('M d, H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No instruments registered</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Batches Tab -->
            <div id="batches-tab" class="hidden">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batch #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Temp/Pressure</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($batches as $batch)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $batch->batch_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($batch->sterilization_method) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->temperature ?? '-' }}°C / {{ $batch->pressure ?? '-' }} psi</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->duration_minutes ?? '-' }} min</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $batch->status === 'sterilized' ? 'bg-green-100 text-green-800' : ($batch->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($batch->status) }}</span></td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->expiry_date?->format('M d, H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if($batch->status === 'processing')
                                            <form action="{{ route('hms.cssd.batch-complete', $batch) }}" method="POST" class="inline">@csrf<button class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded">Complete</button></form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">No sterilization batches</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Instrument Modal -->
    <div id="addInstrumentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Add Instrument</h3>
            <form action="{{ route('hms.cssd.instrument-store') }}" method="POST" class="space-y-4">
                @csrf
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label><input type="text" name="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label><input type="text" name="category" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Surgical, Diagnostic, etc."></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label><input type="number" name="quantity" value="1" min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg">Save</button>
                    <button type="button" onclick="document.getElementById('addInstrumentModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
