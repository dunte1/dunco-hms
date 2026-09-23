<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.wards.index') }}" class="hover:text-blue-600">Wards</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $ward->code }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-hospital text-blue-600 mr-3"></i>
                        {{ $ward->name }}
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
                        <span class="ml-3 px-2 py-1 text-xs font-semibold rounded-full {{ $typeColors[$ward->ward_type] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($ward->ward_type) }}
                        </span>
                    </h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.wards.edit', $ward) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg">
                        <i class="fa fa-edit mr-1"></i> Edit
                    </a>
                    <a href="{{ route('hms.wards.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Ward Details -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ward Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Code:</span>
                        <span class="font-mono font-semibold text-blue-600 dark:text-blue-400 ml-1">{{ $ward->code }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Type:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-1">{{ ucfirst($ward->ward_type) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Department:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-1">{{ $ward->department->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Nurse In Charge:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-1">{{ $ward->nurseInCharge->name ?? 'Not Assigned' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Capacity:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-1">{{ $ward->capacity }} beds</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        @if($ward->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 ml-1">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 ml-1">Inactive</span>
                        @endif
                    </div>
                    @if($ward->description)
                        <div class="md:col-span-2">
                            <span class="text-gray-500">Description:</span>
                            <span class="font-medium text-gray-900 dark:text-white ml-1">{{ $ward->description }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Occupancy Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Beds</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ward->capacity }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-green-600">Available</p>
                    <p class="text-2xl font-bold text-green-600">{{ $ward->available_beds_count }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-red-600">Occupied</p>
                    <p class="text-2xl font-bold text-red-600">{{ $ward->occupied_beds_count }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-blue-600">Occupancy Rate</p>
                    @php $rate = $ward->occupancy_rate; @endphp
                    <p class="text-2xl font-bold {{ $rate >= 90 ? 'text-red-600' : ($rate >= 70 ? 'text-yellow-600' : 'text-blue-600') }}">{{ $rate }}%</p>
                </div>
            </div>

            <!-- Beds List -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Beds in this Ward</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bed Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($ward->beds as $bed)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $bed->bed_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $bed->bedType->title ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $bed->room ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($bed->is_available)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Occupied</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center">
                                        <i class="fa fa-bed text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500">No beds assigned to this ward yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
