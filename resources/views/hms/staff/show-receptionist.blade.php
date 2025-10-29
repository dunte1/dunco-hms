<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-user-tie text-teal-600 mr-3"></i>
                    Receptionist Details
                </h1>
                <div class="flex gap-2">
                    <a href="{{ route('hms.staff.receptionists.edit', $receptionist) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        <i class="fa fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('hms.staff.receptionists') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Profile Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-32"></div>
                <div class="px-6 pb-6">
                    <div class="flex items-end -mt-16 mb-4">
                        <div class="h-32 w-32 bg-white dark:bg-gray-700 rounded-full border-4 border-white dark:border-gray-800 flex items-center justify-center shadow-xl">
                            <span class="text-5xl font-bold text-teal-600">
                                {{ substr($receptionist->first_name, 0, 1) }}{{ substr($receptionist->last_name, 0, 1) }}
                            </span>
                        </div>
                        <div class="ml-6 mb-4">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $receptionist->first_name }} {{ $receptionist->last_name }}
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400">{{ $receptionist->receptionist_id }}</p>
                        </div>
                    </div>

                    <!-- Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Personal Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center border-b pb-2">
                                <i class="fa fa-user text-teal-600 mr-2"></i> Personal Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Date of Birth:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($receptionist->date_of_birth)->format('M d, Y') }}
                                        ({{ \Carbon\Carbon::parse($receptionist->date_of_birth)->age }} years)
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Gender:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($receptionist->gender) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center border-b pb-2">
                                <i class="fa fa-address-book text-teal-600 mr-2"></i> Contact Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <i class="fa fa-envelope text-gray-400 mr-3"></i>
                                    <span class="text-gray-900 dark:text-white">{{ $receptionist->email }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fa fa-phone text-gray-400 mr-3"></i>
                                    <span class="text-gray-900 dark:text-white">{{ $receptionist->phone }}</span>
                                </div>
                                <div class="flex items-start">
                                    <i class="fa fa-map-marker-alt text-gray-400 mr-3 mt-1"></i>
                                    <span class="text-gray-900 dark:text-white">{{ $receptionist->address }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center border-b pb-2">
                                <i class="fa fa-briefcase text-teal-600 mr-2"></i> Employment Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Joining Date:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($receptionist->joining_date)->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Shift:</span>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $receptionist->shift == 'day' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($receptionist->shift == 'night' ? 'bg-indigo-100 text-indigo-800' : 
                                           'bg-purple-100 text-purple-800') }}">
                                        {{ ucfirst($receptionist->shift) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Monthly Salary:</span>
                                    <span class="font-bold text-teal-600">
                                        @if($receptionist->salary)
                                            ${{ number_format($receptionist->salary, 2) }}
                                        @else
                                            <span class="text-gray-400">Not set</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        @if($receptionist->notes)
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center border-b pb-2">
                                <i class="fa fa-sticky-note text-teal-600 mr-2"></i> Notes
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $receptionist->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex gap-3">
                        <a href="{{ route('hms.staff.receptionists.edit', $receptionist) }}" class="flex-1 text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                            <i class="fa fa-edit mr-2"></i> Edit Information
                        </a>
                        <form action="{{ route('hms.staff.receptionists.destroy', $receptionist) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this receptionist? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                <i class="fa fa-trash mr-2"></i> Delete Receptionist
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

