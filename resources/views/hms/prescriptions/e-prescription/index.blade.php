<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-file-prescription text-blue-600 mr-3"></i>E-Prescriptions</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage electronic prescriptions</p>
                </div>
                <a href="{{ route('hms.prescriptions.e-prescription.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> New Prescription
                </a>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($prescriptions as $prescription)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">#{{ $prescription->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $prescription->patient->full_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Dr. {{ $prescription->doctor->first_name ?? 'N/A' }} {{ $prescription->doctor->last_name ?? '' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $prescription->prescription_date?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $prescription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ ucfirst($prescription->status) }}</span></td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('hms.prescriptions.e-prescription.show', $prescription) }}" class="text-blue-600 hover:text-blue-700 mr-2"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('hms.prescriptions.e-prescription.edit', $prescription) }}" class="text-green-600 hover:text-green-700"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No e-prescriptions found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($prescriptions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $prescriptions->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
