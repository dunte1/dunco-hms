@extends('layouts.app')

@section('title', 'Performance Appraisals')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-chart-line text-cyan-600 mr-3"></i> Performance Appraisals
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track and evaluate employee performance</p>
        </div>
        <a href="{{ route('hms.hr.appraisals.create') }}" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> New Appraisal
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by employee name..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500">
            </div>
            <div>
                <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-cyan-500 focus:border-cyan-500">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
            </div>
            <div>
                <a href="{{ route('hms.hr.appraisals.index') }}" class="block w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-center rounded-lg">
                    <i class="fa-solid fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Appraisals Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 h-2"></div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Appraiser</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($appraisals as $appraisal)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-cyan-100 dark:bg-cyan-900 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-semibold text-cyan-600 dark:text-cyan-400">
                                            {{ substr($appraisal->employee->first_name ?? 'E', 0, 1) }}{{ substr($appraisal->employee->last_name ?? '', 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $appraisal->employee->first_name ?? 'N/A' }} {{ $appraisal->employee->last_name ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $appraisal->appraiser->first_name ?? 'N/A' }} {{ $appraisal->appraiser->last_name ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $appraisal->appraisal_date?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($appraisal->overall_rating)
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-solid fa-star {{ $i <= $appraisal->overall_rating ? 'text-amber-400' : 'text-gray-300' }} text-sm"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ $appraisal->overall_rating }}/5)</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Not rated</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$appraisal->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ str_replace('_', ' ', ucfirst($appraisal->status ?? 'draft')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('hms.hr.appraisals.show', $appraisal) }}" class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 mr-3">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('hms.hr.appraisals.edit', $appraisal) }}" class="text-green-600 hover:text-green-900 dark:text-green-400">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fa-solid fa-chart-line text-6xl text-gray-400 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">No appraisals found</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Create your first performance appraisal</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appraisals->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $appraisals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
