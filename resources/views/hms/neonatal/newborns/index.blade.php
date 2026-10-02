<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-child text-amber-600 mr-3"></i> Newborns
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Neonatal registry with mother linkage</p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left">Baby</th>
                                <th class="px-4 py-3 text-left">Sex</th>
                                <th class="px-4 py-3 text-left">DOB</th>
                                <th class="px-4 py-3 text-left">Weight (g)</th>
                                <th class="px-4 py-3 text-left">GA (wks)</th>
                                <th class="px-4 py-3 text-left">Apgar 1/5/10</th>
                                <th class="px-4 py-3 text-left">Mother</th>
                                <th class="px-4 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($newborns as $newborn)
                                <tr>
                                    <td class="px-4 py-3">{{ $newborn->baby_name ?: optional($newborn->patient)->full_name }}</td>
                                    <td class="px-4 py-3">{{ $newborn->sex }}</td>
                                    <td class="px-4 py-3">{{ optional($newborn->date_of_birth)->format('d M Y') }} {{ $newborn->time_of_birth }}</td>
                                    <td class="px-4 py-3">{{ $newborn->birth_weight_grams }}</td>
                                    <td class="px-4 py-3">{{ $newborn->gestational_age_weeks }}</td>
                                    <td class="px-4 py-3">{{ $newborn->apgar_1_min }}/{{ $newborn->apgar_5_min }}/{{ $newborn->apgar_10_min }}</td>
                                    <td class="px-4 py-3">{{ optional(optional($newborn->patient)->first_name) ? optional($newborn->patient)->full_name : ($newborn->mother_patient_id ? 'MRN #'.$newborn->mother_patient_id : '—') }}</td>
                                    <td class="px-4 py-3">{{ $newborn->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">No newborns registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
