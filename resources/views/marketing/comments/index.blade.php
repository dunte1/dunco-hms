@extends('layouts.app')

@section('title', 'Comment Moderation')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-comments text-teal-600 mr-3"></i> Comment Moderation
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Review and respond to social media comments</p>
        </div>
        <div class="flex items-center gap-3">
            @if($pendingCount > 0)
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-amber-100 text-amber-800">
                    <i class="fa-solid fa-clock mr-1"></i> {{ $pendingCount }} pending
                </span>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>Posted</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <select name="platform" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Platforms</option>
                    <option value="facebook" {{ request('platform') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="instagram" {{ request('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="twitter" {{ request('platform') == 'twitter' ? 'selected' : '' }}>Twitter</option>
                    <option value="linkedin" {{ request('platform') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('marketing.comments.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fa-solid fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Comments List -->
    <div class="space-y-4">
        @forelse($replies as $reply)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                @php
                                    $platformColors = [
                                        'facebook' => 'bg-blue-100 text-blue-800',
                                        'instagram' => 'bg-pink-100 text-pink-800',
                                        'twitter' => 'bg-sky-100 text-sky-800',
                                        'linkedin' => 'bg-blue-100 text-blue-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $platformColors[$reply->platform] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($reply->platform ?? 'Unknown') }}
                                </span>
                                @php
                                    $statusStyles = [
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'approved' => 'bg-blue-100 text-blue-800',
                                        'posted' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusStyles[$reply->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($reply->status) }}
                                </span>
                                @if($reply->sentiment)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fa-solid fa-face-smile mr-1"></i> {{ ucfirst($reply->sentiment) }}
                                    </span>
                                @endif
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-3">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $reply->original_comment }}</p>
                            </div>

                            @if($reply->approved_reply)
                                <div class="bg-teal-50 dark:bg-teal-900/30 border-l-4 border-teal-500 rounded-lg p-4 mb-3">
                                    <p class="text-xs font-semibold text-teal-600 mb-1">Reply:</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $reply->approved_reply }}</p>
                                </div>
                            @endif

                            <p class="text-xs text-gray-400">
                                Reviewed by {{ $reply->reviewer->name ?? 'Unreviewed' }} · {{ $reply->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 ml-4">
                            @if($reply->status === 'pending')
                                <form method="POST" action="{{ route('marketing.comments.approve', $reply) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg">
                                        <i class="fa-solid fa-check mr-1"></i> Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('marketing.comments.reject', $reply) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg">
                                        <i class="fa-solid fa-times mr-1"></i> Reject
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-12 text-center">
                <i class="fa-solid fa-comments text-6xl text-gray-400 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 dark:text-white">No comments to moderate</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">All caught up! New comments will appear here.</p>
            </div>
        @endforelse
    </div>

    @if($replies->hasPages())
        <div class="mt-6">
            {{ $replies->links() }}
        </div>
    @endif
</div>
@endsection
