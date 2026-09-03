@extends('admin.layouts.app')

@section('content')
    <div class="mb-4">
        @include('admin.partials.stats', ['stats' => $stats])
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Recent Radiology Requests -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Radiology Requests</h3>
                @if($recentRequests->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentRequests as $request)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $request->patient->full_name }}</p>
                                    <p class="text-sm text-gray-600">Dr. {{ $request->doctor->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->request_date->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($request->status === 'completed') bg-green-100 text-green-800
                                        @elseif($request->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($request->status === 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No recent radiology requests</p>
                @endif
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Requests</h3>
                @if($pendingRequests->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingRequests as $request)
                            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $request->patient->full_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $request->test->test_name ?? 'Unknown Test' }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->request_date->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No pending requests</p>
                @endif
            </div>
        </div>

        <!-- Popular Tests -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Popular Tests</h3>
                @if($popularTests->count() > 0)
                    <div class="space-y-3">
                        @foreach($popularTests as $test)
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $test->test_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $test->category->name ?? 'Uncategorized' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-blue-600">{{ $test->requests_count }} requests</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No test data available</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('hms.radiology.tests.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-900">Manage Tests</p>
                        <p class="text-xs text-blue-700">Add, edit, or view radiology tests</p>
                    </div>
                </a>

                <a href="{{ route('hms.radiology.requests.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-900">Radiology Requests</p>
                        <p class="text-xs text-green-700">Manage imaging requests</p>
                    </div>
                </a>

                <a href="{{ route('hms.radiology.tests.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-purple-900">Reports</p>
                        <p class="text-xs text-purple-700">View radiology analytics</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection


