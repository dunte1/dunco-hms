<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Marketing Posts
            </h2>
            <a href="{{ route('marketing.posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i class="fa fa-plus mr-2"></i>Create Post
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <form method="GET" class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" 
                        class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    <select name="status" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    <select name="platform" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                        <option value="">All Platforms</option>
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="twitter">Twitter</option>
                        <option value="linkedin">LinkedIn</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Filter</button>
                </div>
            </form>

            <!-- Posts Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($posts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Platform</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($posts as $post)
                                        <tr>
                                            <td class="px-4 py-3">{{ $post->title }}</td>
                                            <td class="px-4 py-3">{{ ucfirst($post->platform ?? 'N/A') }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $post->status === 'published' ? 'green' : ($post->status === 'approved' ? 'blue' : 'gray') }}-100">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">{{ $post->created_at->diffForHumans() }}</td>
                                            <td class="px-4 py-3">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('marketing.posts.show', $post) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                                    <a href="{{ route('marketing.posts.edit', $post) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                                    @if($post->status === 'draft')
                                                        <form action="{{ route('marketing.posts.approve', $post) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-800">Approve</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $posts->links() }}</div>
                    @else
                        <p class="text-gray-500">No posts found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

