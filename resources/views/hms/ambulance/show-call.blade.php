<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.ambulance.calls') }}" class="hover:text-orange-600">Ambulance Calls</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $call->call_number }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-phone-alt text-orange-600 mr-3"></i>{{ $call->call_number }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.ambulance.edit-call', $call) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.ambulance.calls') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Call Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Call #:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $call->call_number }}</span></div>
                    <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $call->status === 'completed' ? 'bg-green-100 text-green-800' : ($call->status === 'dispatched' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">{{ ucfirst($call->status) }}</span></div>
                    <div><span class="text-gray-500">Caller:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $call->caller_name }}</span></div>
                    <div><span class="text-gray-500">Caller Phone:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $call->caller_phone }}</span></div>
                    <div><span class="text-gray-500">Call Time:</span> <span class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($call->call_time)->format('M d, Y h:i A') }}</span></div>
                    <div><span class="text-gray-500">Ambulance:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $call->ambulance->vehicle_number ?? 'Not Assigned' }}</span></div>
                    <div class="md:col-span-2"><span class="text-gray-500">Pickup:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $call->pickup_address }}</span></div>
                    <div class="md:col-span-2"><span class="text-gray-500">Destination:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $call->destination_address }}</span></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
