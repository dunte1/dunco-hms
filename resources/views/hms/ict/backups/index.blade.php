<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-database text-blue-600 mr-2 sm:mr-3"></i>
                        ICT Backups
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Manual and automated backup records
                    </p>
                </div>
                <form method="POST" action="{{ route('hms.ict.backups.store') }}">
                    @csrf
                    <input type="hidden" name="run_backup" value="1">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium"
                            onclick="return confirm('Run a backup now?');">
                        <i class="fa fa-play mr-2"></i> Run Backup Now
                    </button>
                </form>
            </div>

            @if(session('status'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('status') }}</p>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total Records</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Today</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['today'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Verified</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['verified'] }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Backup Log</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Location</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Notes</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($backups as $backup)
                                <tr>
                                    <td class="px-5 py-4 text-sm text-gray-900 dark:text-white">{{ $backup->created_at?->format('M d, Y H:i') }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $backup->backup_type ?: 'full' }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300 font-mono text-xs">{{ $backup->backup_location ?: '—' }}</td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $backup->status === 'completed' || $backup->verified ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $backup->status ?: ($backup->verified ? 'verified' : 'recorded') }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ $backup->notes ?: '—' }}</td>
                                    <td class="px-5 py-4">
                                        <form method="POST" action="{{ route('hms.ict.backups.verify', $backup) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sm text-blue-600 hover:text-blue-800" title="Verify">
                                                <i class="fa fa-check-circle"></i> Verify
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-500">
                                        No backup records yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $backups->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
