<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-user-md text-blue-600 mr-3"></i>
                        Doctor Details
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Complete doctor information and performance</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('hms.doctors.edit', $doctor) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        <i class="fa fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('hms.doctors.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Doctor Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="text-center mb-6">
                            <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-5xl font-bold shadow-lg">
                                {{ substr($doctor->first_name, 0, 1) }}{{ substr($doctor->last_name, 0, 1) }}
                            </div>
                            <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h2>
                            @if($doctor->department)
                                <p class="text-blue-600 dark:text-blue-400 font-medium">{{ $doctor->department->name }}</p>
                            @endif
                        </div>

                        <div class="space-y-4">
                            @if($doctor->qualification)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fa fa-graduation-cap text-blue-600 mr-3"></i>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Qualification</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $doctor->qualification }}</span>
                                </div>
                            @endif

                            @if($doctor->years_experience)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fa fa-award text-blue-600 mr-3"></i>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Experience</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $doctor->years_experience }} years</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-calendar text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Joined</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $doctor->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Quick Actions</h3>
                            <div class="space-y-2">
                                <button class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                    <i class="fa fa-calendar-alt mr-2"></i> View Schedule
                                </button>
                                <button class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                    <i class="fa fa-users mr-2"></i> View Patients
                                </button>
                                <button class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                                    <i class="fa fa-chart-line mr-2"></i> Performance Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Information & Records -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Contact Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-address-book text-blue-600 mr-2"></i> Contact Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Email Address</label>
                                <div class="flex items-center text-gray-900 dark:text-white">
                                    <i class="fa fa-envelope text-blue-600 mr-2"></i>
                                    {{ $doctor->email ?: 'Not provided' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Phone Number</label>
                                <div class="flex items-center text-gray-900 dark:text-white">
                                    <i class="fa fa-phone text-blue-600 mr-2"></i>
                                    {{ $doctor->phone ?: 'Not provided' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Details -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-briefcase text-blue-600 mr-2"></i> Professional Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Department</label>
                                <div class="text-gray-900 dark:text-white">{{ $doctor->department->name ?? 'Not assigned' }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Qualification</label>
                                <div class="text-gray-900 dark:text-white">{{ $doctor->qualification ?: 'Not specified' }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Years of Experience</label>
                                <div class="text-gray-900 dark:text-white">
                                    {{ $doctor->years_experience ? $doctor->years_experience . ' years' : 'Not specified' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Registration Date</label>
                                <div class="text-gray-900 dark:text-white">{{ $doctor->created_at->format('F d, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics & Performance -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-chart-bar text-blue-600 mr-2"></i> Performance Statistics
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">0</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">Total Patients</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400">0</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">This Month</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">0</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">Appointments</div>
                            </div>
                            <div class="text-center p-4 bg-orange-50 dark:bg-orange-900 rounded-lg">
                                <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">0</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">Surgeries</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity (Placeholder) -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-history text-blue-600 mr-2"></i> Recent Activity
                        </h3>
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fa fa-clipboard-list text-4xl mb-2"></i>
                            <p>No recent activity to display</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

