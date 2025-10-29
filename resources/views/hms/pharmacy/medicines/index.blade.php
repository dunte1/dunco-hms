<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-pills text-green-600 mr-3"></i>
                        Medicines Inventory
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage pharmacy medicines and stock levels</p>
                </div>
                <a href="{{ route('hms.pharmacy.medicines.create') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> Add Medicine
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Medicines</p>
                            <p class="text-3xl font-bold mt-2">{{ $medicines->total() }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-pills text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">In Stock</p>
                            <p class="text-3xl font-bold mt-2">{{ $medicines->where('stock_quantity', '>', 0)->count() }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Low Stock</p>
                            <p class="text-3xl font-bold mt-2">{{ $medicines->where('stock_quantity', '<=', 'minimum_stock')->count() }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-exclamation-triangle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Categories</p>
                            <p class="text-3xl font-bold mt-2">{{ $medicines->unique('category_id')->count() }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-layer-group text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search medicines..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <select name="category"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                            <option value="">All Categories</option>
                            @foreach($medicines->unique('category_id') as $med)
                                @if($med->category)
                                    <option value="{{ $med->category->id }}">{{ $med->category->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="stock_status"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                            <option value="">All Stock Status</option>
                            <option value="in_stock">In Stock</option>
                            <option value="low_stock">Low Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                            <i class="fa fa-filter mr-2"></i> Filter
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('hms.pharmacy.medicines.index') }}" class="block w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-center rounded-lg">
                            <i class="fa fa-redo mr-2"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Medicines Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Medicine</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Form & Strength</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Expiry</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($medicines as $medicine)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                                <i class="fa fa-pills text-green-600 dark:text-green-400"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $medicine->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $medicine->generic_name ?? 'No generic name' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                            {{ $medicine->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $medicine->dosage_form }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $medicine->strength ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $isLowStock = $medicine->stock_quantity <= $medicine->minimum_stock;
                                            $isOutOfStock = $medicine->stock_quantity == 0;
                                        @endphp
                                        <div class="flex items-center">
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                                {{ $isOutOfStock ? 'bg-red-100 text-red-800' : 
                                                   ($isLowStock ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-green-100 text-green-800') }}">
                                                {{ $medicine->stock_quantity }}
                                            </span>
                                            @if($isLowStock && !$isOutOfStock)
                                                <i class="fa fa-exclamation-triangle text-yellow-600 ml-2" title="Low Stock"></i>
                                            @elseif($isOutOfStock)
                                                <i class="fa fa-times-circle text-red-600 ml-2" title="Out of Stock"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                                        ${{ number_format($medicine->unit_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $medicine->expiry_date?->format('M d, Y') ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('hms.pharmacy.medicines.edit', $medicine) }}" 
                                           class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 mr-3"
                                           title="Edit Medicine">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ route('hms.pharmacy.medicines.show', $medicine) }}" 
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3"
                                           title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <button onclick="if(confirm('Are you sure you want to delete this medicine?')) { document.getElementById('delete-form-{{ $medicine->id }}').submit(); }" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                title="Delete Medicine">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $medicine->id }}" action="{{ route('hms.pharmacy.medicines.destroy', $medicine) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i class="fa fa-pills text-6xl text-gray-400 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">No medicines found</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Add your first medicine to get started</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($medicines->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        {{ $medicines->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
