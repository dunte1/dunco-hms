<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2"><a href="{{ route('hms.mrd.index') }}" class="hover:text-blue-600">MRD</a><i class="fa fa-chevron-right text-xs"></i><span>{{ $file->file_number }}</span></div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-folder-open text-amber-600 mr-3"></i>{{ $file->file_number }}</h1>
                </div>
                <div class="flex gap-3">
                    @if($file->status === 'in_library')
                        <form action="{{ route('hms.mrd.issue', $file) }}" method="POST">@csrf<button class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg"><i class="fa fa-sign-out-alt mr-1"></i> Issue</button></form>
                    @endif
                    @if($file->status === 'issued')
                        <form action="{{ route('hms.mrd.return', $file) }}" method="POST">@csrf<button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-sign-in-alt mr-1"></i> Return</button></form>
                    @endif
                    <a href="{{ route('hms.mrd.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">File Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $file->patient->first_name ?? '' }} {{ $file->patient->last_name ?? '' }}</span></div>
                            <div><span class="text-gray-500">Type:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $file->file_type)) }}</span></div>
                            <div><span class="text-gray-500">Location:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $file->physical_location ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $file->status === 'in_library' ? 'bg-green-100 text-green-800' : ($file->status === 'issued' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst(str_replace('_', ' ', $file->status)) }}</span></div>
                            <div><span class="text-gray-500">Access Count:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $file->access_count }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Movement History</h3>
                        <div class="space-y-3">
                            @forelse($file->movements as $movement)
                                <div class="text-sm border-l-2 border-amber-400 pl-3">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($movement->action) }}</p>
                                    <p class="text-gray-500 text-xs">{{ $movement->created_at->format('M d, Y H:i') }} by {{ $movement->performedByUser?->name ?? 'System' }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">No movement history</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
