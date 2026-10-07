@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                <i class="fa fa-sync-alt text-indigo-600 mr-3"></i>
                Data Sync & Backup Scheduler
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Operational view of automated backups and recovery readiness</p>
        </div>
        <a href="{{ route('hms.settings.backup') }}"
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition inline-flex items-center">
            <i class="fa fa-database mr-2"></i> Open Backup & Restore
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <i class="fa fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Schedule Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-xs uppercase text-gray-500 font-semibold">Archives</div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['archive_count'] ?? 0 }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-xs uppercase text-gray-500 font-semibold">Total Size</div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($stats['total_size_mb'] ?? 0, 2) }} MB</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-xs uppercase text-gray-500 font-semibold">Last Backup</div>
            <div class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $stats['last_backup_at'] ?? 'Not yet created' }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-xs uppercase text-gray-500 font-semibold">Schedule</div>
            <div class="text-lg font-bold text-gray-800 dark:text-gray-100">
                @if(!empty($stats['schedule_enabled']))
                    Daily {{ $stats['schedule'] ?? '02:00' }}
                @else
                    Disabled
                @endif
            </div>
        </div>
    </div>

    <!-- Configuration Summary -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Backup Configuration</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="p-3 border rounded-lg">
                <div class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Storage Disk</div>
                <div class="text-gray-600 dark:text-gray-400"><code>{{ $stats['disk'] ?? 'backups' }}</code> → {{ $stats['path'] ?? storage_path('app/backups') }}</div>
            </div>
            <div class="p-3 border rounded-lg">
                <div class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Encryption</div>
                <div class="text-gray-600 dark:text-gray-400">
                    @if(!empty($stats['encrypted']))
                        <span class="text-green-600 font-semibold">Enabled (archive password set)</span>
                    @else
                        <span class="text-amber-600 font-semibold">Disabled — set BACKUP_ARCHIVE_PASSWORD in .env</span>
                    @endif
                </div>
            </div>
            <div class="p-3 border rounded-lg">
                <div class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Retention (keep all)</div>
                <div class="text-gray-600 dark:text-gray-400">{{ $stats['retention_days'] ?? 7 }} days (longer-term strategy applied by <code>backup:clean</code>)</div>
            </div>
            <div class="p-3 border rounded-lg">
                <div class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Engine</div>
                <div class="text-gray-600 dark:text-gray-400">Spatie laravel-backup (database + uploaded files)</div>
            </div>
        </div>
        <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
            Schedule time is controlled by <code>BACKUP_SCHEDULE_TIME</code> (default 02:00). Cleanup and monitoring run via <code>backup:clean</code> and <code>backup:monitor</code>.
        </div>
    </div>

    <!-- Recent Backups -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Backups</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Verified</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($records as $record)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ optional($record->created_at)->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate">
                                {{ $record->backup_location }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $record->file_size_mb ? $record->file_size_mb . ' MB' : '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($record->status === 'completed')
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">completed</span>
                                @elseif($record->status === 'failed')
                                    <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">failed</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">{{ $record->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $record->verified ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                No backups recorded yet. Create one from Backup & Restore.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @can('manage backups')
            <div class="mt-4">
                <form method="POST" action="{{ route('hms.settings.backup.create') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                        <i class="fa fa-download mr-2"></i> Create Manual Backup Now
                    </button>
                </form>
            </div>
        @endcan
    </div>

    <!-- Information -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center">
            <i class="fa fa-info-circle text-blue-600 mr-2"></i> About Backups & Recovery
        </h3>
        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
            <p>Regular backups ensure your data is safe and can be recovered in case of:</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>System failures or crashes</li>
                <li>Data corruption or accidental deletion</li>
                <li>Security breaches or ransomware attacks</li>
                <li>Natural disasters</li>
            </ul>
            <p class="mt-3"><strong>Best Practice:</strong> Keep encrypted off-server copies of backup archives and test restoration regularly from Backup & Restore.</p>
            <p class="mt-2 text-xs">Cross-site data synchronization is not part of the backup engine. Use Backup & Restore for recovery, and schedule jobs via <code>php artisan schedule:work</code> or system cron.</p>
        </div>
    </div>
</div>
@endsection
