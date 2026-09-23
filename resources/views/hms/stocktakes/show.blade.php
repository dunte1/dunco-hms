<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.stocktakes.index') }}" class="hover:text-blue-600">Stocktakes</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $stocktake->stocktake_number }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-clipboard-check text-teal-600 mr-3"></i>{{ $stocktake->stocktake_number }}</h1>
                </div>
                <div class="flex gap-3">
                    @if($stocktake->status === 'completed')
                        <form action="{{ route('hms.stocktakes.approve', $stocktake) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Approve this stocktake and generate stock adjustments for variances?')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-check mr-1"></i> Approve</button>
                        </form>
                    @endif
                    @if($stocktake->status === 'approved')
                        <form action="{{ route('hms.stocktakes.adjust-stock', $stocktake) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('This will adjust stock levels to match the physical counts. Continue?')" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-sliders-h mr-1"></i> Adjust Stock</button>
                        </form>
                    @endif
                    <a href="{{ route('hms.stocktakes.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"><i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
            @endif

            <!-- Stocktake Info -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Store</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stocktake->store->name ?? 'N/A' }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Date</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stocktake->stocktake_date->format('M d, Y') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        {{ $stocktake->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                           ($stocktake->status === 'completed' ? 'bg-blue-100 text-blue-800' :
                           'bg-green-100 text-green-800') }}">
                        {{ ucfirst(str_replace('_', ' ', $stocktake->status)) }}
                    </span>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Variances</p>
                    <p class="text-lg font-bold {{ $stocktake->variance_count > 0 ? 'text-orange-600' : 'text-green-600' }}">{{ $stocktake->variance_count }} item(s)</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                <div class="lg:col-span-3 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Stocktake Details</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="text-gray-500">Stocktake #:</span> <span class="font-medium text-blue-600">{{ $stocktake->stocktake_number }}</span></div>
                        <div><span class="text-gray-500">Total Items:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $stocktake->items->count() }}</span></div>
                        <div><span class="text-gray-500">Performed By:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $stocktake->performer->name ?? 'N/A' }}</span></div>
                        @if($stocktake->approver)
                            <div><span class="text-gray-500">Approved By:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $stocktake->approver->name }}</span></div>
                        @endif
                        @if($stocktake->completed_at)
                            <div><span class="text-gray-500">Completed:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $stocktake->completed_at->format('M d, Y H:i') }}</span></div>
                        @endif
                        @if($stocktake->approved_at)
                            <div><span class="text-gray-500">Approved:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $stocktake->approved_at->format('M d, Y H:i') }}</span></div>
                        @endif
                        @if($stocktake->notes)
                            <div class="md:col-span-2"><span class="text-gray-500">Notes:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $stocktake->notes }}</span></div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Stocktake Items</h3>
                    @if($stocktake->status === 'in_progress')
                        <span class="text-sm text-yellow-600"><i class="fa fa-edit mr-1"></i> Enter physical counts below</span>
                    @endif
                </div>
                <form method="POST" action="{{ route('hms.stocktakes.update-items', $stocktake) }}" id="stocktakeForm">
                    @csrf
                    @method('PUT')
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicine</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">System Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Physical Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($stocktake->items as $index => $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $item->medicine->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $item->system_quantity }}</td>
                                        <td class="px-6 py-4">
                                            @if($stocktake->status === 'in_progress')
                                                <input type="number" name="items[{{ $item->id }}][physical_quantity]" value="{{ $item->physical_quantity }}" min="0" class="w-24 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm physical-qty" data-system="{{ $item->system_quantity }}">
                                            @else
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->physical_quantity ?? '-' }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 variance-cell">
                                            @if($item->variance !== null && $item->variance != 0)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->variance > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $item->variance > 0 ? '+' : '' }}{{ $item->variance }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-500">0</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($stocktake->status === 'in_progress')
                                                <input type="text" name="items[{{ $item->id }}][remarks]" value="{{ $item->remarks }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm" placeholder="Optional remarks">
                                            @else
                                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $item->remarks ?? '-' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No items in this stocktake</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($stocktake->status === 'in_progress')
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-4">
                            <button type="submit" name="action" value="save" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg"><i class="fa fa-save mr-2"></i> Save Progress</button>
                            <button type="submit" name="action" value="complete" onclick="return confirm('Complete this stocktake? You won\'t be able to edit items after completing.')" class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg"><i class="fa fa-check-double mr-2"></i> Complete Stocktake</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.physical-qty').forEach(input => {
            input.addEventListener('input', function() {
                const system = parseInt(this.dataset.system);
                const physical = parseInt(this.value) || 0;
                const variance = physical - system;
                const cell = this.closest('tr').querySelector('.variance-cell');
                if (variance === 0) {
                    cell.innerHTML = '<span class="text-sm text-gray-500">0</span>';
                } else {
                    const cls = variance > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                    const sign = variance > 0 ? '+' : '';
                    cell.innerHTML = `<span class="px-2 py-1 text-xs font-semibold rounded-full ${cls}">${sign}${variance}</span>`;
                }
            });
        });
    </script>
</x-app-layout>
