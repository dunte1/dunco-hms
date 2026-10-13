<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-syringe text-emerald-600 mr-3"></i>
                        Paediatric Immunizations
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Immunization schedules due or overdue for children</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('vaccination.administer') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-syringe mr-2"></i> Administer Vaccine
                    </a>
                    <a href="{{ route('public-health.immunizations.schedule') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-calendar-check mr-2"></i> Immunization Schedule
                    </a>
                </div>
            </div>

            @if(session('success') || session('status'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('success') ?: session('status') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Due List</h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $schedules->total() }} schedules</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vaccine</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dose</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($schedules as $schedule)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $schedule->patient->full_name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $schedule->patient->patient_no ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $schedule->vaccine->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        Dose {{ $schedule->dose_number ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($schedule->due_date)->format('M d, Y') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColor = match($schedule->status) {
                                                'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                                                'due' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                                                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                            {{ ucfirst($schedule->status ?? 'unknown') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if(in_array($schedule->status, ['due', 'overdue'], true))
                                            <form method="POST" action="{{ route('hms.paediatrics.immunizations.complete', $schedule) }}" class="inline" onsubmit="return confirm('Mark this immunization as completed?');">
                                                @csrf
                                                <input type="hidden" name="batch_number" value="{{ $schedule->batch_number ?? '' }}">
                                                <input type="hidden" name="site" value="{{ $schedule->site ?? 'left_arm' }}">
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg">
                                                    <i class="fa fa-check mr-1"></i> Mark Completed
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 dark:text-gray-500">
                                            <i class="fa fa-syringe text-6xl mb-4"></i>
                                            <p class="text-lg font-medium">No immunizations due</p>
                                            <p class="text-sm mt-2">All scheduled immunizations are up to date, or no schedules exist yet.</p>
                                            <a href="{{ route('vaccination.administer') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                                                <i class="fa fa-plus mr-2"></i> Administer Vaccine
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($schedules->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $schedules->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
