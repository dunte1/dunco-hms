<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-exclamation-triangle text-red-600 mr-3"></i>
                        Drug Interactions & Allergies
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage drug interaction rules and patient allergy records</p>
                </div>
                <a href="{{ route('hms.drug-interactions.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> Add Interaction Rule
                </a>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Rules</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-red-600">Critical</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['critical'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-orange-600">Severe</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['severe'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-yellow-600">Moderate</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['moderate'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-green-600">Mild</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['mild'] }}</p>
                </div>
            </div>

            <!-- Interactions Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Drug A</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Drug B</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Severity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Description</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($interactions as $interaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $interaction->drugA->name }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $interaction->drugB->name }}</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $interaction->severity_badge }}">{{ ucfirst($interaction->severity) }}</span></td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">{{ $interaction->description }}</td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('hms.drug-interactions.show', $interaction) }}" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs"><i class="fa fa-eye"></i></a>
                                            <a href="{{ route('hms.drug-interactions.edit', $interaction) }}" class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs"><i class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <i class="fa fa-exclamation-triangle text-5xl text-gray-400 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">No drug interaction rules defined</p>
                                        <a href="{{ route('hms.drug-interactions.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg"><i class="fa fa-plus mr-2"></i> Add First Rule</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($interactions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $interactions->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
