@extends('layouts.app')

@section('title', 'Public Holidays')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-calendar-days text-rose-600 mr-3"></i> Public Holidays
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage public holidays for {{ now()->year }}</p>
        </div>
        <a href="{{ route('hms.hr.public-holidays.create') }}" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> Add Holiday
        </a>
    </div>

    <!-- Year Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <select name="year" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-rose-500 focus:border-rose-500">
                    @for($y = now()->year + 1; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg">
                    <i class="fa-solid fa-filter mr-2"></i> Filter Year
                </button>
                <a href="{{ route('hms.hr.public-holidays.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fa-solid fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Holidays Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-rose-500 to-rose-600 h-2"></div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Holiday Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Recurring</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($publicHolidays as $holiday)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-rose-100 dark:bg-rose-900 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-calendar-day text-rose-600 dark:text-rose-400"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $holiday->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $holiday->date?->format('l, M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $holiday->description ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($holiday->is_recurring ?? false)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Yes</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($holiday->is_active ?? true)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('hms.hr.public-holidays.edit', $holiday) }}" class="text-rose-600 hover:text-rose-900 dark:text-rose-400 mr-3">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <button onclick="if(confirm('Delete this holiday?')) { document.getElementById('delete-holiday-{{ $holiday->id }}').submit(); }" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <form id="delete-holiday-{{ $holiday->id }}" action="{{ route('hms.hr.public-holidays.destroy', $holiday) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fa-solid fa-calendar-days text-6xl text-gray-400 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">No public holidays found</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Add public holidays for {{ now()->year }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($publicHolidays->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $publicHolidays->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
