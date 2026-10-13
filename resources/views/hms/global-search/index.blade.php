<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-search text-blue-600 mr-2 sm:mr-3"></i>
                        Global Search
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Search patients, appointments, staff, invoices, lab requests, and prescriptions</p>
                </div>
            </div>

            <form method="GET" action="{{ route('hms.global-search') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-5">
                <label for="q" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input
                        id="q"
                        name="q"
                        type="search"
                        value="{{ $query }}"
                        placeholder="Patient name, patient no, phone, invoice number, lab request, diagnosis…"
                        class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                        autofocus
                    >
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                        <i class="fas fa-magnifying-glass mr-2"></i> Search
                    </button>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Minimum 2 characters. Results are limited to 10 per module.</p>
            </form>

            @if($hasQuery)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Results</h2>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ count($results) }} found for “{{ $query }}”</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Title</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Description</th>
                                    <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Open</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($results as $result)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200">
                                                <i class="fas {{ $result['icon'] }} mr-1.5"></i>
                                                {{ $result['type'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $result['title'] }}
                                        </td>
                                        <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $result['description'] }}
                                        </td>
                                        <td class="px-5 py-4 text-right whitespace-nowrap">
                                            <a href="{{ $result['url'] }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                                View <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-500">
                                            No matching records found for “{{ $query }}”.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5">
                    <div class="flex items-start">
                        <i class="fas fa-circle-info text-blue-500 mr-3 mt-0.5"></i>
                        <div class="text-sm text-blue-800 dark:text-blue-200">
                            <p class="font-medium">Start typing to search</p>
                            <p class="mt-1">Look up a patient by name, phone, or patient number; find appointments, staff, invoices, lab requests, and prescriptions in one place.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
