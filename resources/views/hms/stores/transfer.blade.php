<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-exchange-alt text-amber-600 mr-3"></i>Inter-Store Transfer
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Transfer stock between stores</p>
                </div>
                <a href="{{ route('hms.stores.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back to Stores</a>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"><i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Transfer Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2"></div>
                        <form method="POST" action="{{ route('hms.stores.transfer-store') }}" class="p-6 space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Store <span class="text-red-500">*</span></label>
                                    <select name="from_store_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500">
                                        <option value="">Select Source Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ old('from_store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }} ({{ $store->code }})</option>
                                        @endforeach
                                    </select>
                                    @error('from_store_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Store <span class="text-red-500">*</span></label>
                                    <select name="to_store_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500">
                                        <option value="">Select Destination Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ old('to_store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }} ({{ $store->code }})</option>
                                        @endforeach
                                    </select>
                                    @error('to_store_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Medicine <span class="text-red-500">*</span></label>
                                    <select name="medicine_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500">
                                        <option value="">Select Medicine</option>
                                        @foreach($medicines as $id => $name)
                                            <option value="{{ $id }}" {{ old('medicine_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('medicine_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantity <span class="text-red-500">*</span></label>
                                    <input type="number" name="quantity" value="{{ old('quantity') }}" required min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500">
                                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Batch Number</label>
                                    <input type="text" name="batch_number" value="{{ old('batch_number') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                                <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500" placeholder="Transfer reason/notes...">{{ old('notes') }}</textarea>
                            </div>
                            <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit" class="flex-1 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-md transition">
                                    <i class="fa fa-exchange-alt mr-2"></i> Execute Transfer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Recent Transfers -->
                <div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Transfers</h3>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[500px] overflow-y-auto">
                            @forelse($transfers as $transfer)
                                <div class="px-6 py-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-mono text-gray-500">{{ $transfer->movement_number }}</span>
                                        <span class="text-xs {{ $transfer->direction === 'out' ? 'text-red-600' : 'text-green-600' }}">{{ ucfirst($transfer->direction) }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $transfer->medicine->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Qty: {{ $transfer->quantity }} | {{ $transfer->created_at->format('M d, H:i') }}</p>
                                    @if($transfer->store && $transfer->toStore)
                                        <p class="text-xs text-gray-400">{{ $transfer->store->name }} → {{ $transfer->toStore->name }}</p>
                                    @endif
                                </div>
                            @empty
                                <div class="px-6 py-8 text-center text-gray-500 text-sm">No transfers yet</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
