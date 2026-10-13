<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-ear-listen text-purple-600 mr-2 sm:mr-3"></i>
                        ENT Records
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ear, nose, and throat examinations</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Records</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Patient</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Diagnosis</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Examiner</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($records as $record)
                                <tr>
                                    <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $record->patient->first_name ?? '' }} {{ $record->patient->last_name ?? '' }}
                                        <div class="text-xs text-gray-500">{{ $record->patient->patient_no ?? '' }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $record->diagnosis ?: '—' }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $record->examiner->name ?? ($record->examiner->first_name ?? '') }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $record->created_at?->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-500">No ENT records yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">{{ $records->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
