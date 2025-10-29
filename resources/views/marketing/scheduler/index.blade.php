<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Post Scheduler</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($scheduledPosts->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Platform</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scheduled</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($scheduledPosts as $scheduled)
                                    <tr>
                                        <td class="px-4 py-3">{{ $scheduled->marketingPost->title }}</td>
                                        <td class="px-4 py-3">{{ ucfirst($scheduled->socialAccount->platform) }}</td>
                                        <td class="px-4 py-3">{{ $scheduled->scheduled_at->format('M d, Y H:i') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $scheduled->status === 'posted' ? 'green' : ($scheduled->status === 'failed' ? 'red' : 'yellow') }}-100">
                                                {{ ucfirst($scheduled->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($scheduled->status === 'pending')
                                                <form action="{{ route('marketing.scheduler.cancel', $scheduled) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">Cancel</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $scheduledPosts->links() }}</div>
                    </div>
                </div>
            @else
                <p class="text-gray-500">No scheduled posts.</p>
            @endif
        </div>
    </div>
</x-app-layout>

