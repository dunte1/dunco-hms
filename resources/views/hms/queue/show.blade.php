<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.queue.index') }}" class="hover:text-indigo-600">Queue Management</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $queue->queue_number }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-ticket-alt text-indigo-600 mr-3"></i>{{ $queue->queue_number }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.queue.edit', $queue) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.queue.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Queue Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Queue #:</span> <span class="font-bold text-indigo-600 text-lg">{{ $queue->queue_number }}</span></div>
                    <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $queue->status === 'waiting' ? 'bg-yellow-100 text-yellow-800' : ($queue->status === 'called' ? 'bg-green-100 text-green-800' : ($queue->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">{{ ucfirst(str_replace('_', ' ', $queue->status)) }}</span></div>
                    <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $queue->patient_name }}</span></div>
                    <div><span class="text-gray-500">Phone:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $queue->patient_phone ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Department:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $queue->department }}</span></div>
                    <div><span class="text-gray-500">Type:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $queue->queue_type)) }}</span></div>
                    <div><span class="text-gray-500">Priority:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $queue->priority === 'emergency' ? 'bg-red-100 text-red-800' : ($queue->priority === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">{{ ucfirst($queue->priority) }}</span></div>
                    <div><span class="text-gray-500">Check-in:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $queue->check_in_time->format('M d, Y h:i A') }}</span></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
