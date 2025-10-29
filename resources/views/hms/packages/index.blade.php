<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-box-open text-emerald-600 mr-3"></i>
                        Health Packages
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage medical service packages and bundles</p>
                </div>
                <a href="{{ route('hms.packages.create') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-lg shadow-md transition flex items-center">
                    <i class="fa fa-plus mr-2"></i> Create Package
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Packages</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total_packages'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-box-open text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Active Packages</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['active_packages'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Value</p>
                            <p class="text-3xl font-bold mt-2">${{ number_format($stats['total_value'], 0) }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-dollar-sign text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Avg Price</p>
                            <p class="text-3xl font-bold mt-2">${{ number_format($stats['avg_package_price'], 0) }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-chart-line text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" placeholder="Search packages..." value="{{ request('search') }}" 
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div class="w-48">
                        <select name="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-500">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition">
                        <i class="fa fa-search mr-2"></i> Search
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('hms.packages.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-semibold transition">
                            <i class="fa fa-times mr-2"></i> Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($packages as $package)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                        <!-- Package Header -->
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-6 text-white">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold mb-2">{{ $package->name }}</h3>
                                    <p class="text-sm opacity-90 line-clamp-2">{{ $package->description }}</p>
                                </div>
                                <span class="px-3 py-1 {{ $package->is_active ? 'bg-green-500' : 'bg-gray-500' }} bg-opacity-80 rounded-full text-xs font-semibold">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex items-baseline">
                                <span class="text-4xl font-bold">${{ number_format($package->price, 0) }}</span>
                                @if($package->duration_days)
                                    <span class="ml-2 text-sm opacity-90">/ {{ $package->duration_days }} days</span>
                                @endif
                            </div>
                        </div>

                        <!-- Package Details -->
                        <div class="p-6">
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fa fa-list-ul text-emerald-600 mr-3 w-4"></i>
                                    <span class="font-semibold">{{ $package->items->count() }} Services Included</span>
                                </div>
                                @if($package->duration_days)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fa fa-calendar text-emerald-600 mr-3 w-4"></i>
                                        <span>Valid for {{ $package->duration_days }} days</span>
                                    </div>
                                @endif
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fa fa-tag text-emerald-600 mr-3 w-4"></i>
                                    <span>Total Value: ${{ number_format($package->items->sum('total_price'), 2) }}</span>
                                </div>
                                @php
                                    $savings = $package->items->sum('total_price') - $package->price;
                                @endphp
                                @if($savings > 0)
                                    <div class="flex items-center text-sm text-green-600 dark:text-green-400 font-semibold">
                                        <i class="fa fa-piggy-bank mr-3 w-4"></i>
                                        <span>Save ${{ number_format($savings, 2) }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('hms.packages.show', $package) }}" 
                                   class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-center transition">
                                    <i class="fa fa-eye mr-1"></i> View Details
                                </a>
                                <a href="{{ route('hms.packages.edit', $package) }}" 
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition"
                                   title="Edit Package">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button onclick="if(confirm('Are you sure you want to delete this package?')) { document.getElementById('delete-form-{{ $package->id }}').submit(); }" 
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition"
                                        title="Delete Package">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $package->id }}" action="{{ route('hms.packages.destroy', $package) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <i class="fa fa-box-open text-6xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No packages found</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Get started by creating your first health package</p>
                        <a href="{{ route('hms.packages.create') }}" class="inline-block px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
                            <i class="fa fa-plus mr-2"></i> Create Package
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($packages->hasPages())
                <div class="mt-6">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
