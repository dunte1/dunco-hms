<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.cssd.index') }}" class="hover:text-cyan-600">CSSD</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $instrument->name }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-sterilize text-cyan-600 mr-3"></i>{{ $instrument->name }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.cssd.edit-instrument', $instrument) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.cssd.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Instrument Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Name:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $instrument->name }}</span></div>
                    <div><span class="text-gray-500">Category:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $instrument->category ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Quantity:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $instrument->quantity }}</span></div>
                    <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $instrument->status === 'available' ? 'bg-green-100 text-green-800' : ($instrument->status === 'in_use' ? 'bg-blue-100 text-blue-800' : ($instrument->status === 'sterilizing' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">{{ ucfirst(str_replace('_', ' ', $instrument->status)) }}</span></div>
                    <div><span class="text-gray-500">Location:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $instrument->location ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Last Sterilized:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $instrument->last_sterilized_at?->format('M d, Y H:i') ?? 'N/A' }}</span></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
