<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center"><i class="fa fa-file-signature text-indigo-600 mr-3"></i>Consent Management</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Patient consent forms for procedures and treatments</p>
                </div>
                <a href="{{ route('hms.consent.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition"><i class="fa fa-plus mr-2"></i> New Consent</a>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Procedure</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Signed</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($consents as $consent)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $consent->patient->first_name ?? '' }} {{ $consent->patient->last_name ?? '' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $consent->consent_type)) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $consent->procedure_name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">Dr. {{ $consent->doctor->first_name ?? '' }} {{ $consent->doctor->last_name ?? '' }}</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $consent->status === 'signed' ? 'bg-green-100 text-green-800' : ($consent->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($consent->status === 'revoked' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">{{ ucfirst($consent->status) }}</span></td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $consent->signed_at?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('hms.consent.show', $consent) }}" class="px-2 py-1 bg-blue-600 text-white rounded text-xs"><i class="fa fa-eye"></i></a>
                                        @if($consent->status === 'pending')
                                            <form action="{{ route('hms.consent.sign', $consent) }}" method="POST" class="inline">@csrf<button class="px-2 py-1 bg-green-600 text-white rounded text-xs"><i class="fa fa-check"></i> Sign</button></form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">No consent forms</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
