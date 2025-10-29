<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Suppliers Management') }}
            </h2>
            <a href="{{ route('hms.inventory.suppliers.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Supplier
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('status'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-200 px-4 py-3 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Suppliers</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_suppliers'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Active</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['active_suppliers'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Blocked</div>
                    <div class="text-2xl font-bold text-red-600">{{ $stats['blocked_suppliers'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Credit</div>
                    <div class="text-2xl font-bold text-blue-600">KES {{ number_format($stats['total_credit_limit'], 2) }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Outstanding</div>
                    <div class="text-2xl font-bold text-orange-600">KES {{ number_format($stats['total_outstanding'], 2) }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search suppliers..." 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <div>
                            <select name="supplier_type" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">All Types</option>
                                <option value="medicines" {{ request('supplier_type') == 'medicines' ? 'selected' : '' }}>Medicines</option>
                                <option value="equipment" {{ request('supplier_type') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="consumables" {{ request('supplier_type') == 'consumables' ? 'selected' : '' }}>Consumables</option>
                                <option value="food" {{ request('supplier_type') == 'food' ? 'selected' : '' }}>Food</option>
                                <option value="general" {{ request('supplier_type') == 'general' ? 'selected' : '' }}>General</option>
                            </select>
                        </div>
                        <div>
                            <select name="status" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex-1">
                                Filter
                            </button>
                            <a href="{{ route('hms.inventory.suppliers.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Suppliers Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Supplier Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Credit Limit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Outstanding</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($suppliers as $supplier)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $supplier->supplier_code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $supplier->display_name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $supplier->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($supplier->supplier_type == 'medicines') bg-purple-100 text-purple-800
                                                @elseif($supplier->supplier_type == 'equipment') bg-blue-100 text-blue-800
                                                @elseif($supplier->supplier_type == 'consumables') bg-green-100 text-green-800
                                                @elseif($supplier->supplier_type == 'food') bg-orange-100 text-orange-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($supplier->supplier_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            <div>{{ $supplier->phone }}</div>
                                            @if($supplier->email)
                                                <div class="text-gray-500 dark:text-gray-400">{{ $supplier->email }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            KES {{ number_format($supplier->credit_limit, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="{{ $supplier->outstanding_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                KES {{ number_format($supplier->outstanding_balance, 2) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($supplier->status == 'active') bg-green-100 text-green-800
                                                @elseif($supplier->status == 'blocked') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($supplier->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('hms.inventory.suppliers.show', $supplier) }}" 
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400">View</a>
                                                <a href="{{ route('hms.inventory.suppliers.edit', $supplier) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</a>
                                                <form method="POST" action="{{ route('hms.inventory.suppliers.destroy', $supplier) }}" 
                                                      onsubmit="return confirm('Are you sure you want to delete this supplier?');" 
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No suppliers found. <a href="{{ route('hms.inventory.suppliers.create') }}" class="text-blue-600 hover:underline">Add your first supplier</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

