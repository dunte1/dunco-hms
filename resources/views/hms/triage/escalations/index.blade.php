<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-exclamation-circle text-orange-600 mr-3"></i>
                        Triage Escalations
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage and track triage escalation workflow</p>
                </div>
                <a href="{{ route('hms.triage.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-arrow-left mr-2"></i> Back to Triage
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="acknowledged" {{ request('status') === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Severity</label>
                        <select name="severity" class="rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500">
                            <option value="">All Severities</option>
                            <option value="moderate" {{ request('severity') === 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="severe" {{ request('severity') === 'severe' ? 'selected' : '' }}>Severe</option>
                            <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        <i class="fa fa-filter mr-1"></i> Filter
                    </button>
                </form>
            </div>

            <!-- Escalations Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Triage #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Severity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Escalated By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($escalations as $escalation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $escalation->patient->first_name }} {{ $escalation->patient->last_name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $escalation->patient->patient_no }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $escalation->triage->triage_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $severityColors = ['moderate' => 'yellow', 'severe' => 'orange', 'critical' => 'red'];
                                        $color = $severityColors[$escalation->severity] ?? 'gray';
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900 dark:text-{{ $color }}-200">
                                        {{ ucfirst($escalation->severity) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = ['pending' => 'red', 'acknowledged' => 'blue', 'resolved' => 'green'];
                                        $statusColor = $statusColors[$escalation->status] ?? 'gray';
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 dark:bg-{{ $statusColor }}-900 dark:text-{{ $statusColor }}-200">
                                        {{ ucfirst($escalation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $escalation->escalatedBy->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $escalation->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($escalation->status === 'pending')
                                    <form method="POST" action="{{ route('hms.triage.escalations.acknowledge', $escalation) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 mr-2">Acknowledge</button>
                                    </form>
                                    @endif
                                    @if(in_array($escalation->status, ['pending', 'acknowledged']))
                                    <button onclick="document.getElementById('resolve-{{ $escalation->id }}').classList.toggle('hidden')" class="text-green-600 hover:text-green-800 dark:text-green-400">Resolve</button>
                                    <div id="resolve-{{ $escalation->id }}" class="hidden mt-2">
                                        <form method="POST" action="{{ route('hms.triage.escalations.resolve', $escalation) }}">
                                            @csrf
                                            <textarea name="resolution_notes" required placeholder="Resolution notes..." class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white text-sm mb-2" rows="2"></textarea>
                                            <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg">Submit Resolution</button>
                                        </form>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fa fa-check-circle text-4xl text-green-400 mb-3 block"></i>
                                    No escalations found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $escalations->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
