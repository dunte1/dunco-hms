<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.stores.index') }}" class="hover:text-blue-600">Stores</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <a href="{{ route('hms.stores.show', $store) }}" class="hover:text-blue-600">{{ $store->name }}</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Batches</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-layer-group text-green-600 mr-3"></i>{{ $store->name }} - Batches</h1>
                </div>
                <div class="flex gap-3">
                    <button onclick="document.getElementById('addBatchModal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-plus mr-1"></i> Add Batch</button>
                    <a href="{{ route('hms.stores.show', $store) }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batch #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicine</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sold</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remaining</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($batches as $batch)
                                @php
                                    $isExpired = $batch->expiry_date && $batch->expiry_date->isPast();
                                    $isExpiring = $batch->expiry_date && $batch->expiry_date->diffInDays(now()) <= 30 && !$isExpired;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $isExpired ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                    <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $batch->batch_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $batch->medicine->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $batch->quantity_sold }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold {{ $batch->remaining_quantity <= 0 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">{{ $batch->remaining_quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">${{ number_format($batch->unit_cost, 2) }}</td>
                                    <td class="px-6 py-4 text-sm {{ $isExpired ? 'text-red-600 font-bold' : ($isExpiring ? 'text-orange-600 font-semibold' : 'text-gray-700') }}">{{ $batch->expiry_date?->format('M d, Y') ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $isExpired ? 'bg-red-100 text-red-800' : ($batch->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ $isExpired ? 'Expired' : ucfirst($batch->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-6 py-12 text-center text-gray-500">No batches in this store</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($batches->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $batches->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Batch Modal -->
    <div id="addBatchModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg mx-4 p-6 max-h-[80vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><i class="fa fa-plus text-green-600 mr-2"></i>Add Medicine Batch</h3>
            <form action="{{ route('hms.stores.batch-store', $store) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medicine *</label>
                    <select name="medicine_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Select Medicine</option>
                        @foreach(\App\Models\Medicine::orderBy('name')->get() as $med)
                            <option value="{{ $med->id }}">{{ $med->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Batch Number *</label><input type="text" name="batch_number" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity *</label><input type="number" name="quantity" required min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unit Cost ($) *</label><input type="number" name="unit_cost" step="0.01" min="0" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unit Price ($) *</label><input type="number" name="unit_price" step="0.01" min="0" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Manufacturing Date</label><input type="date" name="manufacturing_date" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiry Date *</label><input type="date" name="expiry_date" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                </div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold">Add Batch & Update Stock</button>
                    <button type="button" onclick="document.getElementById('addBatchModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
