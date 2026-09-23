<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.requisitions.index') }}" class="hover:text-blue-600">Requisitions</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $requisition->requisition_number }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-file-import text-purple-600 mr-3"></i>{{ $requisition->requisition_number }}</h1>
                </div>
                <div class="flex gap-3">
                    @if($requisition->status === 'pending')
                        <button onclick="document.getElementById('approveModal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-check mr-1"></i> Approve</button>
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg"><i class="fa fa-times mr-1"></i> Reject</button>
                    @endif
                    @if($requisition->status === 'approved')
                        <button onclick="document.getElementById('fulfillModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"><i class="fa fa-truck mr-1"></i> Fulfill</button>
                    @endif
                    <a href="{{ route('hms.requisitions.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"><i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
            @endif

            <!-- Requisition Info -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Requisition Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Requisition #:</span> <span class="font-medium text-blue-600">{{ $requisition->requisition_number }}</span></div>
                            <div><span class="text-gray-500">Status:</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $requisition->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                       ($requisition->status === 'approved' ? 'bg-blue-100 text-blue-800' :
                                       ($requisition->status === 'fulfilled' ? 'bg-green-100 text-green-800' :
                                       'bg-red-100 text-red-800')) }}">
                                    {{ ucfirst($requisition->status) }}
                                </span>
                            </div>
                            <div><span class="text-gray-500">From Store:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->requestingStore->name ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">To Store:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->supplyingStore->name ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Reason:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->reason ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Requested By:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->requester->name ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Date:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->created_at->format('M d, Y H:i') }}</span></div>
                            @if($requisition->approved_at)
                                <div><span class="text-gray-500">Approved At:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->approved_at->format('M d, Y H:i') }}</span></div>
                            @endif
                            @if($requisition->fulfilled_at)
                                <div><span class="text-gray-500">Fulfilled At:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->fulfilled_at->format('M d, Y H:i') }}</span></div>
                            @endif
                            @if($requisition->notes)
                                <div class="md:col-span-2"><span class="text-gray-500">Notes:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->notes }}</span></div>
                            @endif
                            @if($requisition->rejection_reason)
                                <div class="md:col-span-2 bg-red-50 dark:bg-red-900/20 p-3 rounded-lg"><span class="text-red-600 font-medium">Rejection Reason:</span> <span class="text-gray-900 dark:text-white">{{ $requisition->rejection_reason }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Summary</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-gray-500">Total Items:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->items->count() }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Requested By:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->requester->name ?? 'N/A' }}</span></div>
                            @if($requisition->approver)
                                <div class="flex justify-between"><span class="text-gray-500">Approved By:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $requisition->approver->name }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Requisition Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicine</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Requested</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Fulfilled</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($requisition->items as $index => $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $item->medicine->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $item->quantity_requested }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $item->quantity_fulfilled ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No items in this requisition</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><i class="fa fa-check-circle text-green-600 mr-2"></i>Approve Requisition</h3>
            <form action="{{ route('hms.requisitions.approve', $requisition) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
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
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><i class="fa fa-times-circle text-red-600 mr-2"></i>Reject Requisition</h3>
            <form action="{{ route('hms.requisitions.reject', $requisition) }}" method="POST" class="space-y-4">
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

    <!-- Fulfill Modal -->
    <div id="fulfillModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><i class="fa fa-truck text-blue-600 mr-2"></i>Fulfill Requisition</h3>
            <form action="{{ route('hms.requisitions.fulfill', $requisition) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplying Store <span class="text-red-500">*</span></label>
                    <select name="supplying_store_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Select Supplying Store</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }} ({{ $store->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Fulfillment notes (optional)..."></textarea>
                </div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Confirm Fulfill</button>
                    <button type="button" onclick="document.getElementById('fulfillModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
