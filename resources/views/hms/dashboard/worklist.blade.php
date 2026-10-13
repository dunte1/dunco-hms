<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Clinician Worklist</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Patients waiting for consultation, lab results, or pharmacy
                    </p>
                </div>
                <a href="{{ route('hms.opd.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">OPD List</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Active OPD</p>
                    <p class="text-2xl font-bold">{{ $worklist['opd_visits']->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">In Queue</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $worklist['queue']->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Lab Pending</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $worklist['lab_pending']->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Pharmacy Pending</p>
                    <p class="text-2xl font-bold text-rose-600">{{ $worklist['pharmacy_pending']->count() }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 font-semibold text-gray-900 dark:text-white">
                        OPD Visits (today)
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($worklist['opd_visits'] as $visit)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $visit->patient->full_name ?? '—' }}</td>
                                        <td class="px-4 py-2">
                                            @php($st = $visit->status ?? 'registered')
                                            <span class="px-2 py-1 text-xs rounded-full {{ $st === 'in_consultation' ? 'bg-emerald-100 text-emerald-800' : ($st === 'lab_pending' ? 'bg-amber-100 text-amber-800' : ($st === 'pharmacy_pending' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800')) }}">{{ $st }}</span>
                                        </td>
                                        <td class="px-4 py-2 text-sm">{{ optional($visit->visit_time ?? $visit->created_at)->format('H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">No active OPD visits</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 font-semibold text-gray-900 dark:text-white">
                            Queue
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Token</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($worklist['queue'] as $q)
                                        <tr>
                                            <td class="px-4 py-2 text-sm font-mono">{{ $q->token_number }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $q->patient->full_name ?? '—' }}</td>
                                            <td class="px-4 py-2"><span class="px-2 py-1 text-xs rounded-full {{ $q->priority === 'emergency' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800' }}">{{ $q->priority }}</span></td>
                                            <td class="px-4 py-2 text-sm">{{ $q->status }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Queue empty</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 font-semibold text-gray-900 dark:text-white">Lab Pending</div>
                            <div class="p-4 space-y-2">
                                @forelse($worklist['lab_pending'] as $lab)
                                    <div class="text-sm border-b border-gray-100 dark:border-gray-700 pb-2">
                                        <div class="font-medium">{{ $lab->request_number }}</div>
                                        <div class="text-gray-500">{{ $lab->patient->full_name ?? '—' }}</div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No pending lab requests</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 font-semibold text-gray-900 dark:text-white">Pharmacy Pending</div>
                            <div class="p-4 space-y-2">
                                @forelse($worklist['pharmacy_pending'] as $rx)
                                    <div class="text-sm border-b border-gray-100 dark:border-gray-700 pb-2">
                                        <div class="font-medium">Rx #{{ $rx->id }}</div>
                                        <div class="text-gray-500">{{ $rx->patient->full_name ?? '—' }}</div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No pending prescriptions</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
