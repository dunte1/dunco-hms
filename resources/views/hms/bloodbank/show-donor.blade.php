<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.bloodbank.donors') }}" class="hover:text-rose-600">Blood Donors</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>{{ $donor->first_name }} {{ $donor->last_name }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-hand-holding-heart text-rose-600 mr-3"></i>{{ $donor->first_name }} {{ $donor->last_name }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.bloodbank.edit-donor', $donor) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.bloodbank.donors') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-rose-500 to-rose-600 rounded-full flex items-center justify-center text-white text-4xl font-bold shadow-lg mb-4">
                            {{ substr($donor->first_name, 0, 1) }}{{ substr($donor->last_name, 0, 1) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $donor->first_name }} {{ $donor->last_name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ $donor->donor_id }}</p>
                        <div class="mt-4 p-3 bg-rose-50 dark:bg-rose-900 rounded-lg">
                            <p class="text-2xl font-bold text-rose-600">{{ $donor->bloodGroup->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">Blood Group</p>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Donor Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Phone:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $donor->phone }}</span></div>
                            <div><span class="text-gray-500">Email:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $donor->email ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Gender:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($donor->gender) }}</span></div>
                            <div><span class="text-gray-500">DOB:</span> <span class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($donor->date_of_birth)->format('M d, Y') }} ({{ \Carbon\Carbon::parse($donor->date_of_birth)->age }}y)</span></div>
                            @if($donor->address)
                                <div class="md:col-span-2"><span class="text-gray-500">Address:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $donor->address }}</span></div>
                            @endif
                            @if($donor->medical_conditions)
                                <div class="md:col-span-2"><span class="text-gray-500">Medical Conditions:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $donor->medical_conditions }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
