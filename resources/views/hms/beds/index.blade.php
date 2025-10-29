<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-procedures text-blue-600 mr-3"></i>
                        Bed Management
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage hospital bed assignments and availability</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.bed-types.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-list mr-2"></i> Bed Types
                    </a>
                    <a href="{{ route('hms.beds.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-plus mr-2"></i> Add Bed
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Beds Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($beds as $bed)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                        Bed {{ $bed->bed_number }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $bed->ward_name }}</p>
                                </div>
                                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fa fa-procedures text-xl"></i>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                    <i class="fa fa-bed text-gray-400 mr-2 w-5"></i>
                                    <span>{{ $bed->bedType->name }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                    <i class="fa fa-dollar-sign text-gray-400 mr-2 w-5"></i>
                                    <span>${{ number_format($bed->bedType->charge_per_day, 2) }}/day</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fa fa-circle text-gray-400 mr-2 w-5 {{ $bed->is_available ? 'text-green-500' : 'text-red-500' }}"></i>
                                    <span class="font-semibold {{ $bed->is_available ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $bed->is_available ? 'Available' : 'Occupied' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                                    <i class="fa fa-eye mr-1"></i> View
                                </button>
                                <button class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                                    <i class="fa fa-edit mr-1"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-procedures text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No beds found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Add a new bed to get started</p>
                        <a href="{{ route('hms.beds.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add First Bed
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($beds->hasPages())
                <div class="mt-6">
                    {{ $beds->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
