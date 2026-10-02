<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-heartbeat text-red-600 mr-3"></i> ICU / HDU
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Critical care admissions and monitoring</p>
                </div>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient..."
                           class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-900 dark:border-gray-700">
                    <select name="status" class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-900 dark:border-gray-700">
                        <option value="">All status</option>
                        <option value="active" @selected(request('status')==='active')>Active</option>
                        <option value="discharged" @selected(request('status')==='discharged')>Discharged</option>
                    </select>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Total ICU/HDU</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Active</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">ICU</p>
                    <p class="text-2xl font-bold">{{ $stats['icu'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">HDU</p>
                    <p class="text-2xl font-bold">{{ $stats['hdu'] }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left">Patient</th>
                                <th class="px-4 py-3 text-left">Unit</th>
                                <th class="px-4 py-3 text-left">Diagnosis</th>
                                <th class="px-4 py-3 text-left">Admitted</th>
                                <th class="px-4 py-3 text-left">Doctor</th>
                                <th class="px-4 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($admissions as $admission)
                                <tr>
                                    <td class="px-4 py-3">
                                        {{ optional($admission->patient)->full_name }}
                                        <div class="text-xs text-gray-500">{{ optional($admission->patient)->patient_no }}</div>
                                    </td>
                                    <td class="px-4 py-3">{{ $admission->unit_type }}</td>
                                    <td class="px-4 py-3">{{ $admission->admission_diagnosis }}</td>
                                    <td class="px-4 py-3">{{ optional($admission->admission_datetime)->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3">{{ optional($admission->admittingDoctor)->full_name ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-xs {{ $admission->status==='active' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $admission->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">No ICU/HDU admissions.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $admissions->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
