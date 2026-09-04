@extends('layouts.app')

@section('title', 'Career Opportunities')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-briefcase text-emerald-600 mr-3"></i> Career Opportunities
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Join our team and make a difference in healthcare</p>
        </div>
        <a href="{{ route('marketing.posts.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> Post a Job
        </a>
    </div>

    <!-- Job Listings -->
    <div class="space-y-4">
        @forelse($jobs ?? [] as $job)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $job->title ?? 'Untitled Position' }}</h3>
                            @if($job->is_active ?? true)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Open</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400 mb-3">
                            @if($job->department)
                                <span><i class="fa-solid fa-building mr-1"></i> {{ $job->department }}</span>
                            @endif
                            @if($job->location)
                                <span><i class="fa-solid fa-location-dot mr-1"></i> {{ $job->location }}</span>
                            @endif
                            @if($job->employment_type)
                                <span><i class="fa-solid fa-clock mr-1"></i> {{ ucfirst($job->employment_type) }}</span>
                            @endif
                            @if($job->salary_range)
                                <span><i class="fa-solid fa-money-bill mr-1"></i> {{ $job->salary_range }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($job->description ?? 'No description available', 200) }}</p>
                    </div>
                    <div class="ml-6 flex-shrink-0">
                        <a href="#" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition">
                            Apply Now
                        </a>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t dark:border-gray-700 flex items-center justify-between text-xs text-gray-400">
                    <span>Posted {{ $job->created_at?->diffForHumans() ?? 'Recently' }}</span>
                    @if($job->closing_date)
                        <span>Deadline: {{ $job->closing_date->format('M d, Y') }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-12 text-center">
                <i class="fa-solid fa-briefcase text-6xl text-gray-400 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 dark:text-white">No open positions at the moment</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Check back soon for new opportunities</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
