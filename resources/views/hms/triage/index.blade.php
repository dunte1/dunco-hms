<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-heartbeat text-red-600 mr-3"></i>
                        Triage
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage patient triage assessments and prioritization</p>
                </div>
                <a href="{{ route('hms.triage.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> New Triage
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Triage</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-clipboard-list text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Today</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['today'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar-day text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Emergency</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['emergency'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-exclamation-triangle text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Urgent</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['urgent'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-bolt text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('hms.triage.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-search mr-1"></i> Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by patient name, triage #, complaint..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-tag mr-1"></i> Priority
                        </label>
                        <select name="priority_level" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                            <option value="">All Priorities</option>
                            <option value="emergency" {{ request('priority_level') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            <option value="urgent" {{ request('priority_level') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="semi_urgent" {{ request('priority_level') == 'semi_urgent' ? 'selected' : '' }}>Semi-Urgent</option>
                            <option value="non_urgent" {{ request('priority_level') == 'non_urgent' ? 'selected' : '' }}>Non-Urgent</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>

                    <div class="flex items-end gap-2 md:col-span-5">
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            <i class="fa fa-search mr-2"></i> Search
                        </button>
                        <a href="{{ route('hms.triage.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Triage Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Triage #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Temp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">BP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pulse</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Chief Complaint</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Triage Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($triages as $triage)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ $triage->triage_number }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                                <i class="fa fa-user text-red-600 dark:text-red-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $triage->patient->first_name ?? 'N/A' }} {{ $triage->patient->last_name ?? '' }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $triage->patient->patient_no ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $triage->priority_level === 'emergency' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                               ($triage->priority_level === 'urgent' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' :
                                               ($triage->priority_level === 'semi_urgent' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                               'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $triage->priority_level)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $triage->temperature ? $triage->temperature . '°C' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $triage->systolic_bp && $triage->diastolic_bp ? $triage->systolic_bp . '/' . $triage->diastolic_bp : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $triage->pulse_rate ? $triage->pulse_rate . ' bpm' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        <div class="max-w-xs truncate">{{ $triage->chief_complaint ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $triage->triaged_at?->format('M d, Y h:i A') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('hms.triage.show', $triage) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('hms.triage.edit', $triage) }}" class="text-green-600 hover:text-green-900 dark:text-green-400" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('hms.triage.destroy', $triage) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this triage record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 dark:text-gray-500">
                                            <i class="fa fa-heartbeat text-6xl mb-4"></i>
                                            <p class="text-lg font-medium">No triage records found</p>
                                            <p class="text-sm mt-2">Start by creating a new triage assessment</p>
                                            <a href="{{ route('hms.triage.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                                <i class="fa fa-plus mr-2"></i> New Triage
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($triages->hasPages())
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $triages->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
