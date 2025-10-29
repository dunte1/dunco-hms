<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Marketing Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Posts -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Posts</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_posts'] }}</p>
                            </div>
                            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                <i class="fa fa-file-text text-blue-600 dark:text-blue-400 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Published Posts -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Published</p>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['published_posts'] }}</p>
                            </div>
                            <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                                <i class="fa fa-check-circle text-green-600 dark:text-green-400 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scheduled Posts -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Scheduled</p>
                                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['scheduled_posts'] }}</p>
                            </div>
                            <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                                <i class="fa fa-clock text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Engagement -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Engagement</p>
                                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($stats['total_engagement']) }}</p>
                            </div>
                            <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                                <i class="fa fa-heart text-purple-600 dark:text-purple-400 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Active Campaigns</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_campaigns'] }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Draft Posts</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['draft_posts'] }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Pending Replies</p>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['pending_replies'] }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Posts -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Posts</h3>
                        <div class="space-y-4">
                            @forelse($recentPosts as $post)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $post->title }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ Str::limit($post->content, 60) }}
                                            </p>
                                            <div class="flex items-center gap-2 mt-2">
                                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $post->status === 'published' ? 'green' : 'gray' }}-100 text-{{ $post->status === 'published' ? 'green' : 'gray' }}-800">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No posts yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Upcoming Schedules -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Upcoming Schedules</h3>
                        <div class="space-y-4">
                            @forelse($upcomingSchedules as $schedule)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $schedule->marketingPost->title }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Platform: {{ $schedule->socialAccount->platform }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fa fa-clock mr-1"></i>{{ $schedule->scheduled_at->format('M d, Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No scheduled posts.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

