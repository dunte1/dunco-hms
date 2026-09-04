@extends('layouts.app')

@section('title', 'Photo Gallery')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-images text-purple-600 mr-3"></i> Photo Gallery
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Browse our hospital facilities and events</p>
        </div>
        <a href="{{ route('marketing.graphics.create') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> Upload Photo
        </a>
    </div>

    <!-- Gallery Categories -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6" x-data="{ activeCategory: 'all' }">
        <div class="flex flex-wrap gap-2">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition">
                All
            </button>
            <button @click="activeCategory = 'facilities'" :class="activeCategory === 'facilities' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition">
                Facilities
            </button>
            <button @click="activeCategory = 'events'" :class="activeCategory === 'events' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition">
                Events
            </button>
            <button @click="activeCategory = 'team'" :class="activeCategory === 'team' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition">
                Our Team
            </button>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($photos ?? [] as $photo)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border overflow-hidden group cursor-pointer"
                 x-data="{ open: false }">
                <div class="aspect-square bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                    @if($photo->image_url ?? $photo->url ?? false)
                        <img src="{{ $photo->image_url ?? $photo->url }}" alt="{{ $photo->title ?? 'Gallery photo' }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                            @click="open = true">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition duration-300 flex items-center justify-center">
                        <i class="fa-solid fa-expand text-white text-2xl opacity-0 group-hover:opacity-100 transition"></i>
                    </div>
                </div>
                <div class="p-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $photo->title ?? 'Untitled' }}</p>
                    @if($photo->category ?? false)
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($photo->category) }}</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-12 text-center">
                <i class="fa-solid fa-images text-6xl text-gray-400 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 dark:text-white">No photos in the gallery</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Upload your first photo to start building the gallery</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
