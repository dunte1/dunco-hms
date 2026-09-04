@extends('layouts.app')

@section('title', 'Graphics & Assets')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-image text-pink-600 mr-3"></i> Graphics & Assets
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage marketing graphics and visual assets</p>
        </div>
        <a href="{{ route('marketing.graphics.create') }}" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> Upload Asset
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-pink-500 focus:border-pink-500">
                    <option value="">All Types</option>
                    <option value="poster" {{ request('type') == 'poster' ? 'selected' : '' }}>Poster</option>
                    <option value="infographic" {{ request('type') == 'infographic' ? 'selected' : '' }}>Infographic</option>
                    <option value="social_media" {{ request('type') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                    <option value="banner" {{ request('type') == 'banner' ? 'selected' : '' }}>Banner</option>
                </select>
            </div>
            <div>
                <select name="campaign_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-pink-500 focus:border-pink-500">
                    <option value="">All Campaigns</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('marketing.graphics.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fa-solid fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Assets Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($assets as $asset)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border overflow-hidden group">
                <div class="aspect-w-16 aspect-h-9 bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                    @if($asset->image_url)
                        <img src="{{ $asset->image_url }}" alt="{{ $asset->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-48 flex items-center justify-center">
                            <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    @if($asset->is_ai_generated)
                        <span class="absolute top-2 right-2 px-2 py-1 text-xs font-semibold rounded-full bg-purple-600 text-white">
                            <i class="fa-solid fa-wand-magic-sparkles mr-1"></i> AI
                        </span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $asset->name }}</h3>
                    <div class="flex items-center justify-between mt-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-200">
                            {{ str_replace('_', ' ', ucfirst($asset->type)) }}
                        </span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('marketing.graphics.show', $asset) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('marketing.graphics.edit', $asset) }}" class="text-green-600 hover:text-green-900 dark:text-green-400">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <button onclick="if(confirm('Delete this asset?')) { document.getElementById('delete-asset-{{ $asset->id }}').submit(); }" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <form id="delete-asset-{{ $asset->id }}" action="{{ route('marketing.graphics.destroy', $asset) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">{{ $asset->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-12 text-center">
                <i class="fa-solid fa-image text-6xl text-gray-400 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 dark:text-white">No graphics found</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Upload your first graphic asset to get started</p>
            </div>
        @endforelse
    </div>

    @if($assets->hasPages())
        <div class="mt-6">
            {{ $assets->links() }}
        </div>
    @endif
</div>
@endsection
