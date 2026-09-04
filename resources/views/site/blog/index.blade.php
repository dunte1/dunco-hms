@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-newspaper text-blue-600 mr-3"></i> Blog Posts
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Read our latest health articles and news</p>
        </div>
        <a href="{{ route('marketing.posts.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> New Post
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles..."
                class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                <i class="fa-solid fa-search mr-2"></i> Search
            </button>
        </form>
    </div>

    <!-- Blog Posts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts ?? [] as $post)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border overflow-hidden group">
                <div class="aspect-w-16 aspect-h-9 bg-gray-100 dark:bg-gray-700">
                    @if($post->featured_image)
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-48 flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800">
                            <i class="fa-solid fa-newspaper text-4xl text-blue-400"></i>
                        </div>
                    @endif
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        @if($post->category ?? false)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $post->category }}</span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $post->created_at?->diffForHumans() }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">{{ $post->title ?? 'Untitled' }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-3">{{ Str::limit($post->excerpt ?? $post->body ?? 'No content available', 120) }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-gray-400">By {{ $post->author->name ?? 'Admin' }}</span>
                        <a href="{{ route('marketing.posts.show', $post) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Read More <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-12 text-center">
                <i class="fa-solid fa-newspaper text-6xl text-gray-400 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 dark:text-white">No blog posts yet</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Create your first blog post to share health insights</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
