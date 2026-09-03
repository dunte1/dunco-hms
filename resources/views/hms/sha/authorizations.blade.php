<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-file-signature text-purple-600 mr-3"></i> SHA Authorizations
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Service authorization requests</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('hms.sha.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Request Authorization -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fa fa-plus-circle text-purple-600 mr-2"></i> Request New Authorization
                </h3>
                <form action="{{ route('hms.sha.authorization.request') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient</label>
                        <select name="patient_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select patient...</option>
                            @foreach(\App\Models\Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']) as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->patient_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SHA Member</label>
                        <select name="sha_member_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select member...</option>
                            @foreach(\App\Models\ShaMember::all(['id', 'sha_member_number', 'first_name', 'last_name']) as $member)
                                <option value="{{ $member->id }}">{{ $member->full_name }} ({{ $member->sha_member_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Service Type</label>
                        <input type="text" name="service_type" required placeholder="e.g. inpatient, outpatient, surgery"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Service Code</label>
                        <input type="text" name="service_code" required placeholder="e.g. SHA-5001"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Diagnosis (ICD-10)</label>
                        <input type="text" name="diagnosis_code" placeholder="e.g. E11.9"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estimated Amount (KES)</label>
                        <input type="number" step="0.01" name="estimated_amount" placeholder="0.00"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="md:col-span-3">
                        <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium">
                            <i class="fa fa-paper-plane mr-2"></i> Submit Authorization Request
                        </button>
                    </div>
                </form>
            </div>

            <!-- Authorizations list -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Authorizations</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($authorizations as $auth)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $auth->authorization_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $auth->patient->first_name ?? '' }} {{ $auth->patient->last_name ?? '' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $auth->service_type }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $auth->diagnosis_code ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $auth->authorized_amount ? number_format($auth->authorized_amount, 2) : 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $auth->status_color }}-100 text-{{ $auth->status_color }}-800">{{ ucfirst($auth->status) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('hms.sha.authorization.show', $auth) }}" class="text-purple-600 hover:text-purple-700">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No authorizations</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($authorizations->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $authorizations->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
