@extends('layouts.app')

@section('title', 'SEO Management')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-magnifying-glass-chart text-emerald-600 mr-3"></i> SEO Management
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Optimize search engine visibility for your content</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-lg">
                    <i class="fa-solid fa-file-lines text-emerald-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total SEO Records</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $seoRecords->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fa-solid fa-newspaper text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Blog Posts</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $blogPosts->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg">
                    <i class="fa-solid fa-key text-amber-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Optimized Pages</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $seoRecords->where('meta_title', '!=', null)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Records Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2"></div>
        <div class="p-6 border-b dark:border-gray-700">
            <form method="GET" class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or keyword..."
                    class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                    <i class="fa-solid fa-search mr-2"></i> Search
                </button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Content</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Meta Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Meta Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Focus Keyword</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($seoRecords as $record)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ class_basename($record->seoable_type ?? 'N/A') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $record->seoable_id }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ Str::limit($record->meta_title ?? 'Not set', 40) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ Str::limit($record->meta_description ?? 'Not set', 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($record->focus_keyword)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">{{ $record->focus_keyword }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('marketing.seo.optimize', ['seoable_type' => class_basename($record->seoable_type), 'seoable_id' => $record->seoable_id]) }}" class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400">
                                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Optimize
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fa-solid fa-magnifying-glass-chart text-6xl text-gray-400 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">No SEO records found</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Optimize your first page to improve search rankings</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($seoRecords->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $seoRecords->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
