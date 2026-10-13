<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-house-medical text-rose-600 mr-2 sm:mr-3"></i>
                        Social Discharge Plans
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Home care and community follow-up plans</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Plans</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Patient</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Home Care</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Follow-up</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($plans as $plan)
                                <tr>
                                    <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $plan->patient->first_name ?? '' }} {{ $plan->patient->last_name ?? '' }}
                                        <div class="text-xs text-gray-500">{{ $plan->patient->patient_no ?? '' }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $plan->status === 'completed' ? 'bg-green-100 text-green-800' : ($plan->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700') }}">
                                            {{ ucfirst($plan->status ?? 'draft') }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ $plan->home_care_needs ?: '—' }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ $plan->follow_up_appointments ?: '—' }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $plan->created_at?->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-500">No discharge plans yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">{{ $plans->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
