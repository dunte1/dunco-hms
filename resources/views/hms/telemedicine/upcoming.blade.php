<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-video text-blue-600 mr-2 sm:mr-3"></i>
                        Upcoming Telemedicine Sessions
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Scheduled virtual consultations</p>
                </div>
                <a href="{{ route('telemedicine.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium">
                    <i class="fa fa-arrow-left mr-2"></i> All Sessions
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Scheduled</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Patient</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Doctor</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Scheduled</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Platform</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($sessions as $session)
                                <tr>
                                    <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $session->patient->first_name ?? '' }} {{ $session->patient->last_name ?? '' }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        Dr. {{ $session->doctor->first_name ?? '' }} {{ $session->doctor->last_name ?? '' }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $session->scheduled_time?->format('M d, Y H:i') ?? '—' }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $session->platform ?: '—' }}</td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($session->status ?? 'scheduled') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-500">No upcoming telemedicine sessions.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
