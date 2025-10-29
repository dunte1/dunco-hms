<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Supplier Details') }}: {{ $supplier->display_name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('hms.inventory.suppliers.edit', $supplier) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Edit Supplier
                </a>
                <a href="{{ route('hms.inventory.suppliers.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Status Badge -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $supplier->display_name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $supplier->supplier_code }}</p>
                    </div>
                    <div>
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($supplier->status == 'active') bg-green-100 text-green-800
                            @elseif($supplier->status == 'blocked') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($supplier->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Supplier Type</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($supplier->supplier_type) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Payment Terms</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $supplier->payment_terms)) }}</dd>
                        </div>
                        @if($supplier->contact_person)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Contact Person</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $supplier->contact_person }}</dd>
                        </div>
                        @endif
                        @if($supplier->tax_number)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Tax Number</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $supplier->tax_number }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Contact Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h4>
                    <dl class="space-y-3">
                        @if($supplier->email)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Email</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:underline">{{ $supplier->email }}</a>
                            </dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Phone</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $supplier->phone }}</dd>
                        </div>
                        @if($supplier->mobile)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Mobile</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $supplier->mobile }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Address</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $supplier->address }}<br>
                                @if($supplier->city){{ $supplier->city }}, @endif
                                @if($supplier->state){{ $supplier->state }}, @endif
                                {{ $supplier->country }}
                                @if($supplier->postal_code)- {{ $supplier->postal_code }}@endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Financial Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Information</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Credit Limit</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">KES {{ number_format($supplier->credit_limit, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Outstanding Balance</dt>
                            <dd class="text-sm font-medium {{ $supplier->outstanding_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                KES {{ number_format($supplier->outstanding_balance, 2) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Available Credit</dt>
                            <dd class="text-sm font-medium text-blue-600">KES {{ number_format($supplier->available_credit, 2) }}</dd>
                        </div>
                        @if($supplier->bank_name)
                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Bank Details</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $supplier->bank_name }}<br>
                                @if($supplier->bank_account_number)Acc: {{ $supplier->bank_account_number }}<br>@endif
                                @if($supplier->bank_branch)Branch: {{ $supplier->bank_branch }}@endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Notes -->
            @if($supplier->notes)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notes</h4>
                <p class="text-gray-700 dark:text-gray-300">{{ $supplier->notes }}</p>
            </div>
            @endif

            <!-- Recent Purchase Orders -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Purchase Orders</h4>
                
                @if($recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">PO Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Order Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $order->po_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $order->order_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    KES {{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($order->status == 'received') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status == 'approved' || $order->status == 'ordered') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('hms.inventory.purchase-orders.show', $order) }}" 
                                       class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No purchase orders yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

