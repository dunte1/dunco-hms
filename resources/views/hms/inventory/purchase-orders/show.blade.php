<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Purchase Order') }}: {{ $purchaseOrder->po_number }}
            </h2>
            <div class="flex space-x-2">
                @if(in_array($purchaseOrder->status, ['draft', 'pending']))
                    <a href="{{ route('hms.inventory.purchase-orders.edit', $purchaseOrder) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        Edit PO
                    </a>
                @endif
                <a href="{{ route('hms.inventory.purchase-orders.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('status'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <!-- PO Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $purchaseOrder->po_number }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">Order Date: {{ $purchaseOrder->order_date->format('M d, Y') }}</p>
                        @if($purchaseOrder->reference_number)
                            <p class="text-sm text-gray-500">Ref: {{ $purchaseOrder->reference_number }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="mb-2">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold
                                @if($purchaseOrder->status == 'received') bg-green-100 text-green-800
                                @elseif($purchaseOrder->status == 'cancelled') bg-red-100 text-red-800
                                @elseif($purchaseOrder->status == 'approved' || $purchaseOrder->status == 'ordered') bg-blue-100 text-blue-800
                                @elseif($purchaseOrder->status == 'pending') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($purchaseOrder->status) }}
                            </span>
                        </div>
                        @if($purchaseOrder->status == 'draft')
                            <form method="POST" action="{{ route('hms.inventory.purchase-orders.submit', $purchaseOrder) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                                    Submit for Approval
                                </button>
                            </form>
                        @elseif($purchaseOrder->status == 'pending')
                            <form method="POST" action="{{ route('hms.inventory.purchase-orders.approve', $purchaseOrder) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                                    Approve PO
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Supplier Info -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Supplier Information</h4>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Company</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $purchaseOrder->supplier->display_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Contact</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $purchaseOrder->supplier->phone }}</dd>
                        </div>
                        @if($purchaseOrder->supplier->email)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Email</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $purchaseOrder->supplier->email }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Order Details -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Details</h4>
                    <dl class="space-y-2">
                        @if($purchaseOrder->expected_delivery_date)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Expected Delivery</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $purchaseOrder->expected_delivery_date->format('M d, Y') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Payment Method</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $purchaseOrder->payment_method)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Payment Status</dt>
                            <dd>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($purchaseOrder->payment_status == 'paid') bg-green-100 text-green-800
                                    @elseif($purchaseOrder->payment_status == 'partially_paid') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $purchaseOrder->payment_status)) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Created By</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $purchaseOrder->creator->name ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Items</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">#</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Item</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">UOM</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Qty</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Unit Price</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($purchaseOrder->items as $index => $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    <div>{{ $item->item_name }}</div>
                                    @if($item->description)
                                        <div class="text-xs text-gray-500">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $item->unit_of_measure }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">{{ $item->quantity_ordered }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white font-medium">{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">Subtotal:</td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">KES {{ number_format($purchaseOrder->subtotal, 2) }}</td>
                            </tr>
                            @if($purchaseOrder->tax_amount > 0)
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-right text-sm text-gray-600 dark:text-gray-400">Tax:</td>
                                <td class="px-4 py-2 text-right text-sm text-gray-900 dark:text-white">KES {{ number_format($purchaseOrder->tax_amount, 2) }}</td>
                            </tr>
                            @endif
                            @if($purchaseOrder->discount_amount > 0)
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-right text-sm text-gray-600 dark:text-gray-400">Discount:</td>
                                <td class="px-4 py-2 text-right text-sm text-red-600">-KES {{ number_format($purchaseOrder->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            @if($purchaseOrder->shipping_cost > 0)
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-right text-sm text-gray-600 dark:text-gray-400">Shipping:</td>
                                <td class="px-4 py-2 text-right text-sm text-gray-900 dark:text-white">KES {{ number_format($purchaseOrder->shipping_cost, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="border-t-2 border-gray-300">
                                <td colspan="5" class="px-4 py-3 text-right text-base font-bold text-gray-900 dark:text-white">Total:</td>
                                <td class="px-4 py-3 text-right text-base font-bold text-blue-600">KES {{ number_format($purchaseOrder->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($purchaseOrder->notes || $purchaseOrder->terms_and_conditions)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($purchaseOrder->notes)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notes</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $purchaseOrder->notes }}</p>
                </div>
                @endif
                @if($purchaseOrder->terms_and_conditions)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Terms & Conditions</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $purchaseOrder->terms_and_conditions }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

