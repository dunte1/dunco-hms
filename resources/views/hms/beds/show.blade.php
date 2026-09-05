<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.beds.index') }}" class="hover:text-blue-600">Bed Management</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Bed {{ $bed->bed_number }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-procedures text-blue-600 mr-3"></i>Bed {{ $bed->bed_number }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.beds.edit', $bed) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.beds.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Bed Information</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Bed Number:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $bed->bed_number }}</span></div>
                            <div><span class="text-gray-500">Ward/Room:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $bed->ward_name }}</span></div>
                            <div><span class="text-gray-500">Bed Type:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $bed->bedType->name ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Daily Charge:</span> <span class="font-semibold text-blue-600">${{ number_format($bed->bedType->charge_per_day ?? 0, 2) }}</span></div>
                            <div><span class="text-gray-500">Status:</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $bed->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $bed->is_available ? 'Available' : 'Occupied' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('hms.beds.edit', $bed) }}" class="block w-full px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-center rounded-lg text-sm"><i class="fa fa-edit mr-2"></i>Edit Bed</a>
                            <a href="{{ route('hms.beds.index') }}" class="block w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-center rounded-lg text-sm"><i class="fa fa-arrow-left mr-2"></i>Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
