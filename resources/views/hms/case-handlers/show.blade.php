<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.case-handlers.index') }}" class="hover:text-purple-600">Case Handlers</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $handler->first_name }} {{ $handler->last_name }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-user-nurse text-purple-600 mr-3"></i>{{ $handler->first_name }} {{ $handler->last_name }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.case-handlers.edit', $handler) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.case-handlers.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Handler Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Name:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $handler->first_name }} {{ $handler->last_name }}</span></div>
                    <div><span class="text-gray-500">Handler ID:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $handler->handler_id }}</span></div>
                    <div><span class="text-gray-500">Email:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $handler->email }}</span></div>
                    <div><span class="text-gray-500">Phone:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $handler->phone }}</span></div>
                    <div><span class="text-gray-500">Specialization:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $handler->specialization ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Status:</span> <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $handler->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $handler->is_active ? 'Active' : 'Inactive' }}</span></div>
                    @if($handler->qualifications)
                        <div class="md:col-span-2"><span class="text-gray-500">Qualifications:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $handler->qualifications }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
