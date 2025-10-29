<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Stock Movement Details') }}: {{ $stockMovement->movement_number }}
            </h2>
            <a href="{{ route('hms.hms.inventory.stock-movements.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Movements
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Movement Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stockMovement->movement_number }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $stockMovement->movement_date->format('F d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($stockMovement->movement_type == 'purchase') bg-green-100 text-green-800
                            @elseif($stockMovement->movement_type == 'sale') bg-blue-100 text-blue-800
                            @elseif($stockMovement->movement_type == 'damage' || $stockMovement->movement_type == 'expiry') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($stockMovement->movement_type) }}
                        </span>
                        <div class="mt-2">
                            @if($stockMovement->direction == 'in')
                                <span class="text-green-600 font-bold text-lg">↑ STOCK IN</span>
                            @else
                                <span class="text-red-600 font-bold text-lg">↓ STOCK OUT</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Medicine Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Medicine Information</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Medicine</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $stockMovement->medicine->name }}</dd>
                        </div>
                        @if($stockMovement->batch_number)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Batch Number</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $stockMovement->batch_number }}</dd>
                        </div>
                        @endif
                        @if($stockMovement->expiry_date)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Expiry Date</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $stockMovement->expiry_date->format('M d, Y') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Quantity Details -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quantity Details</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Quantity</dt>
                            <dd class="text-2xl font-bold {{ $stockMovement->direction == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $stockMovement->direction == 'in' ? '+' : '-' }}{{ $stockMovement->quantity }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Stock Before</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $stockMovement->stock_before }} units</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Stock After</dt>
                            <dd class="text-sm font-medium text-blue-600">{{ $stockMovement->stock_after }} units</dd>
                        </div>
                    </dl>
                </div>

                <!-- Cost Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cost Information</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Unit Cost</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">KES {{ number_format($stockMovement->unit_cost, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Total Cost</dt>
                            <dd class="text-lg font-bold text-blue-600">KES {{ number_format($stockMovement->total_cost, 2) }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Additional Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Additional Information</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Recorded By</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $stockMovement->user->name }}</dd>
                        </div>
                        @if($stockMovement->purchaseOrder)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Purchase Order</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('hms.inventory.purchase-orders.show', $stockMovement->purchaseOrder) }}" 
                                   class="text-blue-600 hover:underline">
                                    {{ $stockMovement->purchaseOrder->po_number }}
                                </a>
                            </dd>
                        </div>
                        @endif
                        @if($stockMovement->from_location || $stockMovement->to_location)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Location</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                @if($stockMovement->from_location)
                                    From: {{ $stockMovement->from_location }}
                                @endif
                                @if($stockMovement->to_location)
                                    → To: {{ $stockMovement->to_location }}
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            @if($stockMovement->reason || $stockMovement->notes)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($stockMovement->reason)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reason</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $stockMovement->reason }}</p>
                </div>
                @endif
                @if($stockMovement->notes)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notes</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $stockMovement->notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

