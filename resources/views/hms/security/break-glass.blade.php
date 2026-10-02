@extends('components.app-layout')

@section('title', 'Break-Glass Access Log')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Break-Glass Emergency Access</h1>
            <p class="text-sm text-gray-500 mt-1">
                Every emergency access is reason-coded, audited, and subject to security review.
                This is not a normal access path.
            </p>
        </div>
        @can('manage break glass events')
            <a href="{{ route('break-glass.create') }}"
               class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                Request Emergency Access
            </a>
        @endcan
    </div>

    @if(session('status'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-left">
                <tr>
                    <th class="px-4 py-3">When</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Reason</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Reviewer</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($events as $event)
                    <tr>
                        <td class="px-4 py-3">{{ $event->created_at?->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $event->user?->name ?? $event->user_id }}</td>
                        <td class="px-4 py-3">
                            @if($event->patient)
                                {{ $event->patient->patient_no }} {{ $event->patient->full_name }}
                            @else
                                â€”
                            @endif
                        </td>
                        <td class="px-4 py-3 max-w-md truncate" title="{{ $event->reason }}">{{ $event->reason }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs
                                @if($event->status === 'approved') bg-green-100 text-green-800
                                @elseif($event->status === 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $event->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $event->reviewer?->name ?? 'â€”' }}</td>
                        <td class="px-4 py-3">
                            @if($event->isPending())
                                @can('manage break glass events')
                                    <form method="POST" action="{{ route('break-glass.approve', $event) }}" class="inline">
                                        @csrf
                                        <button class="text-green-600 hover:underline mr-2">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('break-glass.reject', $event) }}" class="inline">
                                        @csrf
                                        <button class="text-red-600 hover:underline">Reject</button>
                                    </form>
                                @endcan
                            @else
                                <span class="text-gray-400 text-xs">Reviewed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">No break-glass events recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $events->links() }}</div>
</div>
@endsection

