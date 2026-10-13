<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1 flex items-center gap-2">
                        <a href="{{ route('hms.dashboard.definitions.index') }}" class="hover:text-indigo-600">Definitions</a>
                        <i class="fa fa-chevron-right text-[10px]"></i>
                        <span class="text-gray-700 dark:text-gray-300">{{ $dashboard->name }}</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $dashboard->name }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 font-mono">{{ $dashboard->slug }}</p>
                </div>
                <a href="{{ route('hms.dashboard.definitions.index') }}"
                   class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium">
                    <i class="fa fa-arrow-left mr-2"></i> Back
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Details</h2>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Status</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($dashboard->status ?? 'draft') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Owner</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $dashboard->owner->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Default</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $dashboard->is_default ? 'Yes' : 'No' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase">Created</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $dashboard->created_at?->format('M d, Y H:i') ?? '—' }}</p>
                    </div>
                    @if($dashboard->description)
                        <div class="sm:col-span-2">
                            <p class="text-gray-500 text-xs uppercase">Description</p>
                            <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $dashboard->description }}</p>
                        </div>
                    @endif
                    @if($dashboard->layout)
                        <div class="sm:col-span-2">
                            <p class="text-gray-500 text-xs uppercase">Layout</p>
                            <pre class="mt-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-xs overflow-x-auto">{{ json_encode($dashboard->layout, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
