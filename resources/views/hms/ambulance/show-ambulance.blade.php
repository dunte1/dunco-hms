<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.ambulance.index') }}" class="hover:text-red-600">Ambulances</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $ambulance->vehicle_number }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-ambulance text-red-600 mr-3"></i>{{ $ambulance->vehicle_number }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.ambulance.edit-ambulance', $ambulance) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.ambulance.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ambulance Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Vehicle #:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $ambulance->vehicle_number }}</span></div>
                    <div><span class="text-gray-500">Type:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst(str_replace('_', ' ', $ambulance->vehicle_type)) }}</span></div>
                    <div><span class="text-gray-500">Driver:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $ambulance->driver_name }}</span></div>
                    <div><span class="text-gray-500">Driver Phone:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $ambulance->driver_phone }}</span></div>
                    <div><span class="text-gray-500">Status:</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $ambulance->is_available ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $ambulance->is_available ? 'Available' : 'In Use' }}
                        </span>
                    </div>
                    @if($ambulance->equipment_list)
                        <div class="md:col-span-2"><span class="text-gray-500">Equipment:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $ambulance->equipment_list }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
