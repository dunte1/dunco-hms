<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-user text-purple-600 mr-3"></i>
                        Visitor Details
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Visitor information and visit history</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($visitor->status === 'checked_in')
                    <form action="{{ route('hms.visitors.check-out', $visitor) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg" onclick="return confirm('Check out this visitor?')">
                            <i class="fa fa-sign-out-alt mr-2"></i> Check Out
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('hms.visitors.badge', $visitor) }}" target="_blank" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg">
                        <i class="fa fa-print mr-2"></i> Print Badge
                    </a>
                    <a href="{{ route('hms.visitors.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
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
                <!-- Visitor Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="text-center mb-6">
                            <div class="w-32 h-32 mx-auto bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white text-5xl font-bold shadow-lg">
                                {{ substr($visitor->visitor_name, 0, 1) }}
                            </div>
                            <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ $visitor->visitor_name }}</h2>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Badge: {{ $visitor->badge_number }}</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-2
                                {{ $visitor->status === 'checked_in' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fa fa-{{ $visitor->status === 'checked_in' ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $visitor->status)) }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-phone text-purple-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Phone</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $visitor->visitor_phone }}
                                </span>
                            </div>

                            @if($visitor->visitor_email)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-envelope text-purple-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Email</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $visitor->visitor_email }}
                                </span>
                            </div>
                            @endif

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-users text-purple-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Type</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst($visitor->visitor_type) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visitor Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Visit Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-info-circle text-purple-600 mr-2"></i> Visit Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Purpose</label>
                                <div class="text-gray-900 dark:text-white">{{ $visitor->purpose }}</div>
                            </div>

                            @if($visitor->department)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Department</label>
                                <div class="text-gray-900 dark:text-white">{{ $visitor->department }}</div>
                            </div>
                            @endif

                            @if($visitor->contact_person)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Contact Person</label>
                                <div class="text-gray-900 dark:text-white">{{ $visitor->contact_person }}</div>
                            </div>
                            @endif

                            @if($visitor->patient_name)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Visiting Patient</label>
                                <div class="text-gray-900 dark:text-white">{{ $visitor->patient_name }}</div>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Check In Time</label>
                                <div class="text-gray-900 dark:text-white">{{ $visitor->check_in_time->format('F d, Y h:i A') }}</div>
                            </div>

                            @if($visitor->check_out_time)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Check Out Time</label>
                                <div class="text-gray-900 dark:text-white">{{ $visitor->check_out_time->format('F d, Y h:i A') }}</div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Duration</label>
                                <div class="text-gray-900 dark:text-white font-semibold">
                                    {{ $visitor->check_in_time->diffForHumans($visitor->check_out_time, true) }}
                                </div>
                            </div>
                            @endif

                            @if($visitor->notes)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Notes</label>
                                <div class="text-gray-900 dark:text-white">{{ $visitor->notes }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Badge Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-id-card text-purple-600 mr-2"></i> Badge Information
                        </h3>
                        <div class="bg-purple-50 dark:bg-purple-900 border border-purple-200 dark:border-purple-700 rounded-lg p-6 text-center">
                            <div class="text-4xl font-bold text-purple-600 dark:text-purple-300 mb-2">{{ $visitor->badge_number }}</div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Visitor Badge Number</p>
                            <a href="{{ route('hms.visitors.badge', $visitor) }}" target="_blank" class="mt-4 inline-block px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                                <i class="fa fa-print mr-2"></i> Print Badge
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

