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
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Schedule automatic backups and data synchronization</p>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Backup Configuration -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Backup Settings</h3>
        
        <form action="#" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="flex items-center mb-2">
                    <input type="checkbox" name="enable_backups" value="1" checked
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable automatic backups</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Backup Frequency
                    </label>
                    <select name="backup_frequency" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Backup Time
                    </label>
                    <input type="time" name="backup_time" value="02:00"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Retention Period (Days)
                </label>
                <input type="number" name="retention_days" value="30" min="1" max="365"
                    placeholder="How long to keep backups"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Backup Storage Location
                </label>
                <select name="storage_location" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="local">Local Storage</option>
                    <option value="cloud">Cloud Storage (Google Drive)</option>
                    <option value="s3">AWS S3</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 mb-3">
                    What to Backup
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="backup_database" value="1" checked
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Database</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="backup_files" value="1" checked
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Uploaded Files</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="backup_reports" value="1"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Reports</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                <i class="fa fa-save mr-2"></i> Save Settings
            </button>
        </form>
    </div>

    <!-- Data Sync -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Data Synchronization</h3>
        
        <form action="#" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="flex items-center mb-2">
                    <input type="checkbox" name="enable_sync" value="1"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable data synchronization</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Sync With
                </label>
                <select name="sync_target" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select sync target</option>
                    <option value="central_server">Central Server</option>
                    <option value="mobile_app">Mobile Application</option>
                    <option value="third_party_api">Third-Party API</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 mb-3">
                    Sync Data Types
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="sync_patients" value="1" checked
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Patients</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="sync_appointments" value="1" checked
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Appointments</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="sync_invoices" value="1"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Invoices</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="sync_records" value="1"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Medical Records</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                <i class="fa fa-sync-alt mr-2"></i> Save Sync Settings
            </button>
        </form>
    </div>

    <!-- Recent Backups -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Backups</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            No backups yet
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                <i class="fa fa-download mr-2"></i> Create Manual Backup Now
            </button>
        </div>
    </div>

    <!-- Information -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center">
            <i class="fa fa-info-circle text-blue-600 mr-2"></i> About Backups & Sync
        </h3>
        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
            <p>Regular backups ensure your data is safe and can be recovered in case of:</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>System failures or crashes</li>
                <li>Data corruption or accidental deletion</li>
                <li>Security breaches or ransomware attacks</li>
                <li>Natural disasters</li>
            </ul>
            <p class="mt-3"><strong>Best Practice:</strong> Keep backups in multiple locations (local and cloud) and test restoration procedures regularly.</p>
        </div>
    </div>
</div>
@endsection
