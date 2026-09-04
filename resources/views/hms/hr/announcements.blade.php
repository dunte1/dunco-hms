@extends('layouts.app')

@section('title', 'HR Announcements')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-bullhorn text-orange-600 mr-3"></i> HR Announcements
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Create and manage staff announcements</p>
        </div>
        <a href="{{ route('hms.hr.announcements.create') }}" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> New Announcement
        </a>
    </div>

    <!-- Status Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('hms.hr.announcements.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-orange-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                All
            </a>
            <a href="{{ route('hms.hr.announcements.index', ['status' => 'active']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                <i class="fa-solid fa-check-circle mr-1"></i> Active
            </a>
            <a href="{{ route('hms.hr.announcements.index', ['status' => 'inactive']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'inactive' ? 'bg-gray-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                <i class="fa-solid fa-pause-circle mr-1"></i> Inactive
            </a>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="space-y-4">
        @forelse($announcements as $announcement)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-12 w-12 bg-orange-100 dark:bg-orange-900 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-bullhorn text-orange-600 dark:text-orange-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $announcement->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    <i class="fa-solid fa-calendar mr-1"></i> {{ $announcement->start_date?->format('M d, Y') ?? 'N/A' }}
                                    @if($announcement->end_date)
                                        — {{ $announcement->end_date->format('M d, Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($announcement->is_active ?? true)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                            <a href="{{ route('hms.hr.announcements.edit', $announcement) }}" class="text-orange-600 hover:text-orange-900 dark:text-orange-400">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-700 dark:text-gray-300">
                        {!! nl2br(e(Str::limit($announcement->content ?? 'No content', 200))) !!}
                    </div>
                    <div class="mt-4 flex items-center gap-3 flex-wrap">
                        @if($announcement->department)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $announcement->department->name }}</span>
                        @endif
                        @if($announcement->priority)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">{{ ucfirst($announcement->priority) }}</span>
                        @endif
                        <span class="text-xs text-gray-400">By {{ $announcement->creator->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-12 text-center">
                <i class="fa-solid fa-bullhorn text-6xl text-gray-400 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 dark:text-white">No announcements found</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Create your first announcement to keep staff informed</p>
            </div>
        @endforelse
    </div>

    @if($announcements->hasPages())
        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
