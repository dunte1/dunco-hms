<x-app-layout>
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-bell mr-3 text-blue-600"></i>
                Notifications Center
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Stay updated with your hospital activities</p>
        </div>
        <div class="flex space-x-3">
            @if($unreadCount > 0)
                <form action="{{ route('hms.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-check-double mr-2"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Notifications</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalCount }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-inbox text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Unread</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-2">{{ $unreadCount }}</p>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <i class="fas fa-bell text-2xl text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Read</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $totalCount - $unreadCount }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-check-circle text-2xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Notifications</h2>
        </div>
        
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($notifications as $notification)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $notification->read_at ? '' : 'bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4 flex-1">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center
                                    {{ $notification->read_at ? 'bg-gray-100 dark:bg-gray-700' : 'bg-blue-100 dark:bg-blue-900' }}">
                                    @php
                                        $iconClass = 'fa-bell';
                                        $iconColor = $notification->read_at ? 'text-gray-600 dark:text-gray-400' : 'text-blue-600 dark:text-blue-400';
                                        
                                        if(isset($notification->data['type'])) {
                                            switch($notification->data['type']) {
                                                case 'appointment':
                                                    $iconClass = 'fa-calendar-check';
                                                    break;
                                                case 'payment':
                                                    $iconClass = 'fa-dollar-sign';
                                                    break;
                                                case 'lab_result':
                                                    $iconClass = 'fa-flask';
                                                    break;
                                                case 'prescription':
                                                    $iconClass = 'fa-pills';
                                                    break;
                                                default:
                                                    $iconClass = 'fa-bell';
                                            }
                                        }
                                    @endphp
                                    <i class="fas {{ $iconClass }} {{ $iconColor }}"></i>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $notification->data['message'] ?? $notification->type }}
                                        </p>
                                        <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500 dark:text-gray-500">
                                            <span class="flex items-center">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            @if(!$notification->read_at)
                                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full font-medium">
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center space-x-2 ml-4">
                            @if(!$notification->read_at)
                                <form action="{{ route('hms.notifications.mark-read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-lg transition" title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('hms.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-bell-slash text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Notifications</h3>
                        <p class="text-gray-600 dark:text-gray-400">You're all caught up! No new notifications at the moment.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

@if(session('success'))
<script>
    // Show success notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    notification.innerHTML = '<i class="fas fa-check-circle mr-2"></i> {{ session("success") }}';
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
</script>
@endif
</x-app-layout>

