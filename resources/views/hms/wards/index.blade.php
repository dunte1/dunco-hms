<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-hospital text-blue-600 mr-3"></i> Ward Management
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage hospital wards, bed assignments, and occupancy</p>
                </div>
                <a href="{{ route('hms.wards.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> Add Ward
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Wards</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-green-600">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-blue-600">Total Beds</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total_beds'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-red-600">Occupied Beds</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['occupied_beds'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('hms.wards.index') }}" class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or code..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="w-full md:w-48">
                        <select name="ward_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All Types</option>
                            @foreach(['general', 'surgical', 'paediatric', 'maternity', 'icu', 'hdu', 'observation'] as $type)
                                <option value="{{ $type }}" {{ request('ward_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-40">
                        <select name="is_active" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All Status</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        <i class="fa fa-search mr-2"></i> Search
                    </button>
                    @if(request('search') || request('ward_type') || request('is_active') !== null)
                        <a href="{{ route('hms.wards.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg">
                            <i class="fa fa-times mr-2"></i> Clear
                        </a>
                    @endif
                </div>
            </form>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nurse In Charge</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Occupied</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Occupancy %</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($wards as $ward)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-blue-600 dark:text-blue-400">{{ $ward->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $ward->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $typeColors = [
                                                'general' => 'bg-gray-100 text-gray-800',
                                                'surgical' => 'bg-red-100 text-red-800',
                                                'paediatric' => 'bg-yellow-100 text-yellow-800',
                                                'maternity' => 'bg-pink-100 text-pink-800',
                                                'icu' => 'bg-purple-100 text-purple-800',
                                                'hdu' => 'bg-indigo-100 text-indigo-800',
                                                'observation' => 'bg-teal-100 text-teal-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $typeColors[$ward->ward_type] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($ward->ward_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $ward->department->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $ward->nurseInCharge->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 dark:text-white">{{ $ward->capacity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 dark:text-white">{{ $ward->occupied_beds_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php $rate = $ward->occupancy_rate; @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $rate >= 90 ? 'bg-red-100 text-red-800' : ($rate >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            {{ $rate }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($ward->is_active)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('hms.wards.show', $ward) }}" class="text-blue-600 hover:text-blue-700 mr-2" title="View"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('hms.wards.edit', $ward) }}" class="text-green-600 hover:text-green-700 mr-2" title="Edit"><i class="fa fa-edit"></i></a>
                                        <form action="{{ route('hms.wards.destroy', $ward) }}" method="POST" class="inline" onsubmit="return confirm('Delete this ward?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700" title="Delete"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-8 text-center">
                                        <i class="fa fa-hospital text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500">No wards found.</p>
                                        <p class="text-sm text-gray-400 mt-1">Try a different search or add a new ward.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($wards->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $wards->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
