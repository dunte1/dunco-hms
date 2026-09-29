<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                <i class="fa fa-id-card text-blue-600 mr-3"></i> Credentialing Dashboard
            </h1>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Doctors</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_doctors'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Verified Qualifications</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['verified_qualifications'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Unverified Qualifications</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['unverified_qualifications'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Expiring Licences</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['expiring_licences'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('hms.credentialing.qualifications') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Qualifications</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage practitioner qualifications</p>
                </a>
                <a href="{{ route('hms.credentialing.licences.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Licences</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Track medical licences</p>
                </a>
                <a href="{{ route('hms.credentialing.privileges.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Privileges</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Grant and revoke privileges</p>
                </a>
                <a href="{{ route('hms.credentialing.oncall.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">On-Call Schedules</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage on-call rosters</p>
                </a>
                <a href="{{ route('hms.credentialing.cme.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">CME Records</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Track continuing education</p>
                </a>
                <a href="{{ route('hms.credentialing.licences.expiring') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Licence Expiry Alerts</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">View expiring licences</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
