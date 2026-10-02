<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa fa-baby text-pink-600 mr-2"></i> Pregnancy #{{ $pregnancy->id }}
                </h1>
                <a href="{{ route('hms.maternity.pregnancies.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-sm">Back</a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Patient</h2>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-gray-500">Name</dt><dd>{{ optional($pregnancy->patient)->full_name }}</dd></div>
                    <div><dt class="text-gray-500">MRN</dt><dd>{{ optional($pregnancy->patient)->patient_no }}</dd></div>
                    <div><dt class="text-gray-500">Gravida/Para</dt><dd>G{{ $pregnancy->gravida }} P{{ $pregnancy->parity }}</dd></div>
                    <div><dt class="text-gray-500">LMP</dt><dd>{{ optional($pregnancy->last_menstrual_date)->format('d M Y') }}</dd></div>
                    <div><dt class="text-gray-500">EDD</dt><dd>{{ optional($pregnancy->estimated_due_date)->format('d M Y') }}</dd></div>
                    <div><dt class="text-gray-500">Gestational weeks</dt><dd>{{ $pregnancy->current_gestational_weeks }}</dd></div>
                    <div><dt class="text-gray-500">Blood group</dt><dd>{{ $pregnancy->blood_group }}</dd></div>
                    <div><dt class="text-gray-500">Rh factor</dt><dd>{{ $pregnancy->rh_factor }}</dd></div>
                    <div><dt class="text-gray-500">HIV status</dt><dd>{{ $pregnancy->hiv_status }}</dd></div>
                    <div><dt class="text-gray-500">Status</dt><dd>{{ $pregnancy->status }}</dd></div>
                    <div class="col-span-2">
                        <dt class="text-gray-500">High risk</dt>
                        <dd>
                            @if($pregnancy->is_high_risk)
                                <span class="text-red-600 font-semibold">YES — {{ $pregnancy->high_risk_reason }}</span>
                            @else
                                No
                            @endif
                        </dd>
                    </div>
                    <div class="col-span-2"><dt class="text-gray-500">Previous complications</dt><dd>{{ $pregnancy->previous_complications ?: '—' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-3">ANC Visits</h2>
                @php $visits = $pregnancy->ancVisits ?? collect(); @endphp
                @if($visits && $visits->count())
                    <table class="min-w-full text-sm">
                        <thead><tr class="text-left text-gray-500 border-b">
                            <th class="py-2">Date</th><th>BP</th><th>Hb</th><th>Fundal</th><th>FHR</th><th>Notes</th>
                        </tr></thead>
                        <tbody>
                            @foreach($visits as $visit)
                                <tr class="border-b">
                                    <td class="py-2">{{ optional($visit->visit_date ?? $visit->created_at)->format('d M Y') }}</td>
                                    <td>{{ $visit->blood_pressure ?? '—' }}</td>
                                    <td>{{ $visit->hb_level ?? $visit->haemoglobin ?? '—' }}</td>
                                    <td>{{ $visit->fundal_height ?? '—' }}</td>
                                    <td>{{ $visit->fetal_heart_rate ?? '—' }}</td>
                                    <td>{{ $visit->notes ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-sm">No ANC visits recorded yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
