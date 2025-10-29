<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Campaigns</h2>
            <a href="{{ route('marketing.campaigns.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i class="fa fa-plus mr-2"></i>Create Campaign
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($campaigns->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($campaigns as $campaign)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-2">{{ $campaign->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($campaign->description, 100) }}</p>
                            <div class="flex justify-between items-center mb-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $campaign->status === 'active' ? 'green' : 'gray' }}-100">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $campaign->start_date->format('M d') }} - {{ $campaign->end_date->format('M d') }}</span>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('marketing.campaigns.show', $campaign) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                <a href="{{ route('marketing.campaigns.edit', $campaign) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">Edit</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $campaigns->links() }}</div>
            @else
                <p class="text-gray-500">No campaigns found.</p>
            @endif
        </div>
    </div>
</x-app-layout>

