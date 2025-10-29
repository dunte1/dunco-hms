<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                View Marketing Post
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('marketing.posts.edit', $post) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    <i class="fa fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('marketing.posts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    <i class="fa fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">{{ $post->title }}</h3>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($post->type) }}
                                </span>
                                @if($post->platform)
                                    <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                        {{ ucfirst($post->platform) }}
                                    </span>
                                @endif
                                <span class="px-3 py-1 text-xs rounded-full bg-{{ $post->status === 'published' ? 'green' : ($post->status === 'approved' ? 'blue' : 'gray') }}-100 text-{{ $post->status === 'published' ? 'green' : ($post->status === 'approved' ? 'blue' : 'gray') }}-800">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right text-sm text-gray-600 dark:text-gray-400">
                            <p>Created: {{ $post->created_at->format('M d, Y H:i') }}</p>
                            <p>By: {{ $post->creator->name ?? 'Unknown' }}</p>
                            @if($post->scheduled_at)
                                <p>Scheduled: {{ $post->scheduled_at->format('M d, Y H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Content</h4>
                        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                            <p class="whitespace-pre-wrap">{{ $post->content }}</p>
                        </div>
                    </div>

                    @if($post->hashtags)
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Hashtags</h4>
                            <p class="text-blue-600 dark:text-blue-400">{{ $post->hashtags }}</p>
                        </div>
                    @endif

                    @if($post->cta_text || $post->cta_url)
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Call-to-Action</h4>
                            <p class="mb-1">{{ $post->cta_text }}</p>
                            @if($post->cta_url)
                                <a href="{{ $post->cta_url }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $post->cta_url }}
                                </a>
                            @endif
                        </div>
                    @endif

                    @if($post->campaign)
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Campaign</h4>
                            <p>{{ $post->campaign->name }}</p>
                        </div>
                    @endif

                    @if($post->is_ai_generated)
                        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                            <p class="text-sm">
                                <i class="fa fa-robot mr-2"></i>This post was AI-generated using {{ $post->ai_model ?? 'AI' }}
                            </p>
                        </div>
                    @endif

                    @if($post->status === 'draft')
                        <form action="{{ route('marketing.posts.approve', $post) }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
                                <i class="fa fa-check mr-2"></i>Approve Post
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

