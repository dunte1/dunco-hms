<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-baby text-pink-600 mr-3"></i> Pregnancies (ANC Register)
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Antenatal registrations and pregnancy records</p>
                </div>
                <a href="{{ route('hms.maternity.pregnancies.store') }}" class="hidden"></a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left">Patient</th>
                                <th class="px-4 py-3 text-left">G/P</th>
                                <th class="px-4 py-3 text-left">LMP</th>
                                <th class="px-4 py-3 text-left">EDD</th>
                                <th class="px-4 py-3 text-left">Weeks</th>
                                <th class="px-4 py-3 text-left">HIV</th>
                                <th class="px-4 py-3 text-left">High risk</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($pregnancies as $pregnancy)
                                <tr>
                                    <td class="px-4 py-3">
                                        {{ optional($pregnancy->patient)->full_name }}
                                        <div class="text-xs text-gray-500">{{ optional($pregnancy->patient)->patient_no }}</div>
                                    </td>
                                    <td class="px-4 py-3">G{{ $pregnancy->gravida }} P{{ $pregnancy->parity }}</td>
                                    <td class="px-4 py-3">{{ optional($pregnancy->last_menstrual_date)->format('d M Y') }}</td>
                                    <td class="px-4 py-3">{{ optional($pregnancy->estimated_due_date)->format('d M Y') }}</td>
                                    <td class="px-4 py-3">{{ $pregnancy->current_gestational_weeks }}</td>
                                    <td class="px-4 py-3">{{ $pregnancy->hiv_status }}</td>
                                    <td class="px-4 py-3">
                                        @if($pregnancy->is_high_risk)
                                            <span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-800">YES</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">No</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $pregnancy->status }}</td>
                                    <td class="px-4 py-3">
                                        <a class="text-blue-600 hover:underline" href="{{ route('hms.maternity.pregnancies.show', $pregnancy) }}">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">No pregnancies registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
