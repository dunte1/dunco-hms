<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.stock-adjustments.index') }}" class="hover:text-blue-600">Stock Adjustments</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $adjustment->adjustment_number }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-sliders-h text-orange-600 mr-3"></i>{{ $adjustment->adjustment_number }}</h1>
                </div>
                <div class="flex gap-3">
                    @if($adjustment->status === 'pending')
                        <button onclick="document.getElementById('approveModal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-check mr-1"></i> Approve</button>
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg"><i class="fa fa-times mr-1"></i> Reject</button>
                    @endif
                    <a href="{{ route('hms.stock-adjustments.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"><i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
            @endif

            <!-- Adjustment Info -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Adjustment Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Adjustment #:</span> <span class="font-medium text-blue-600">{{ $adjustment->adjustment_number }}</span></div>
                            <div><span class="text-gray-500">Status:</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $adjustment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                       ($adjustment->status === 'approved' ? 'bg-green-100 text-green-800' :
                                       'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($adjustment->status) }}
                                </span>
                            </div>
                            <div><span class="text-gray-500">Medicine:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->medicine->name ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Store:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->store->name ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Qty Adjustment:</span>
                                <span class="font-semibold {{ $adjustment->quantity_adjustment > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $adjustment->quantity_adjustment > 0 ? '+' : '' }}{{ $adjustment->quantity_adjustment }}
                                </span>
                            </div>
                            <div><span class="text-gray-500">Type:</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $adjustment->adjustment_type === 'addition' ? 'bg-green-100 text-green-800' :
                                       ($adjustment->adjustment_type === 'deduction' ? 'bg-red-100 text-red-800' :
                                       ($adjustment->adjustment_type === 'stocktake' ? 'bg-blue-100 text-blue-800' :
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($adjustment->adjustment_type) }}
                                </span>
                            </div>
                            <div><span class="text-gray-500">Stock Before:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->stock_before ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Stock After:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->stock_after ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Requested By:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->requester->name ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Date:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->created_at->format('M d, Y H:i') }}</span></div>
                            @if($adjustment->approved_at)
                                <div><span class="text-gray-500">Approved By:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->approver->name ?? 'N/A' }}</span></div>
                                <div><span class="text-gray-500">Approved At:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->approved_at->format('M d, Y H:i') }}</span></div>
                            @endif
                            @if($adjustment->stocktake)
                                <div><span class="text-gray-500">Linked Stocktake:</span> <span class="font-medium text-blue-600">{{ $adjustment->stocktake->stocktake_number }}</span></div>
                            @endif
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500">Reason:</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $adjustment->reason }}</p>
                        </div>
                        @if($adjustment->rejection_reason)
                            <div class="mt-4 bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                                <span class="text-sm text-red-600 font-medium">Rejection Reason:</span>
                                <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $adjustment->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Impact Summary</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between"><span class="text-gray-500">Medicine:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->medicine->name ?? 'N/A' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Store:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->store->name ?? 'N/A' }}</span></div>
                            @if($adjustment->stock_before !== null && $adjustment->stock_after !== null)
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between"><span class="text-gray-500">Before:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $adjustment->stock_before }}</span></div>
                                    <div class="flex justify-center text-gray-400 my-1"><i class="fa fa-arrow-down"></i></div>
                                    <div class="flex justify-between"><span class="text-gray-500">After:</span> <span class="font-bold {{ $adjustment->stock_after > $adjustment->stock_before ? 'text-green-600' : 'text-red-600' }}">{{ $adjustment->stock_after }}</span></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><i class="fa fa-check-circle text-green-600 mr-2"></i>Approve Adjustment</h3>
            <form action="{{ route('hms.stock-adjustments.approve', $adjustment) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <p class="text-sm text-gray-600 dark:text-gray-400">This will apply the stock adjustment of <strong>{{ $adjustment->quantity_adjustment > 0 ? '+' : '' }}{{ $adjustment->quantity_adjustment }}</strong> units for <strong>{{ $adjustment->medicine->name ?? 'N/A' }}</strong> in <strong>{{ $adjustment->store->name ?? 'N/A' }}</strong>.</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Approval notes (optional)..."></textarea>
                </div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold">Confirm Approve</button>
                    <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><i class="fa fa-times-circle text-red-600 mr-2"></i>Reject Adjustment</h3>
            <form action="{{ route('hms.stock-adjustments.reject', $adjustment) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason <span class="text-red-500">*</span></label>
                    <textarea name="rejection_reason" rows="3" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Reason for rejection..."></textarea>
                </div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold">Confirm Reject</button>
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
