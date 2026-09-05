<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.case-handlers.index') }}" class="hover:text-purple-600">Case Handlers</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <a href="{{ route('hms.case-handlers.show', $case->handler) }}" class="hover:text-purple-600">Cases</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Case #{{ $case->case_number }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-folder-open text-purple-600 mr-3"></i>Case #{{ $case->case_number }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.case-handlers.edit-case', $case) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.case-handlers.show', $case->handler) }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Case Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Case #:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $case->case_number }}</span></div>
                    <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $case->status === 'open' ? 'bg-blue-100 text-blue-800' : ($case->status === 'closed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($case->status) }}</span></div>
                    <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $case->patient->full_name ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Handler:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $case->handler->first_name ?? 'N/A' }} {{ $case->handler->last_name ?? '' }}</span></div>
                    <div><span class="text-gray-500">Type:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $case->case_type ?? 'N/A')) }}</span></div>
                    <div><span class="text-gray-500">Created:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $case->created_at->format('M d, Y') }}</span></div>
                    @if($case->description)
                        <div class="md:col-span-2"><span class="text-gray-500">Description:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $case->description }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
