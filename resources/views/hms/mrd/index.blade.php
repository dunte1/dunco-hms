<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center"><i class="fa fa-folder-open text-amber-600 mr-3"></i>Medical Records Department</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track and manage physical medical records</p>
                </div>
                <button onclick="document.getElementById('addFileModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-md transition"><i class="fa fa-plus mr-2"></i> Register File</button>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
                <form method="GET" class="flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search file # or patient..." class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    <select name="status" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"><option value="">All Status</option><option value="in_library">In Library</option><option value="issued">Issued</option><option value="returned">Returned</option><option value="archived">Archived</option></select>
                    <button class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">File #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($files as $file)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $file->file_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $file->patient->first_name ?? '' }} {{ $file->patient->last_name ?? '' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $file->file_type)) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $file->physical_location ?? '-' }}</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $file->status === 'in_library' ? 'bg-green-100 text-green-800' : ($file->status === 'issued' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst(str_replace('_', ' ', $file->status)) }}</span></td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('hms.mrd.show', $file) }}" class="px-2 py-1 bg-blue-600 text-white rounded text-xs"><i class="fa fa-eye"></i></a>
                                        @if($file->status === 'in_library')
                                            <form action="{{ route('hms.mrd.issue', $file) }}" method="POST" class="inline">@csrf<button class="px-2 py-1 bg-yellow-600 text-white rounded text-xs"><i class="fa fa-sign-out-alt"></i> Issue</button></form>
                                        @endif
                                        @if($file->status === 'issued')
                                            <form action="{{ route('hms.mrd.return', $file) }}" method="POST" class="inline">@csrf<button class="px-2 py-1 bg-green-600 text-white rounded text-xs"><i class="fa fa-sign-in-alt"></i> Return</button></form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No MRD files registered</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Add File Modal -->
    <div id="addFileModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Register MRD File</h3>
            <form action="{{ route('hms.mrd.store') }}" method="POST" class="space-y-4">
                @csrf
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Patient ID *</label><input type="number" name="patient_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File Type *</label><select name="file_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><option value="discharge_summary">Discharge Summary</option><option value="lab_report">Lab Report</option><option value="imaging">Imaging</option><option value="consent">Consent</option><option value="operation_note">Operation Note</option><option value="correspondence">Correspondence</option><option value="other">Other</option></select></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Physical Location</label><input type="text" name="physical_location" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Shelf, Room, etc."></div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg">Save</button>
                    <button type="button" onclick="document.getElementById('addFileModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
