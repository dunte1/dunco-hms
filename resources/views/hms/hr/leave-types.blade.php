@extends('layouts.app')

@section('title', 'Leave Types')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-calendar-xmark text-teal-600 mr-3"></i> Leave Types
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Configure leave categories and policies</p>
        </div>
        <a href="{{ route('hms.hr.leave-types.create') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> Add Leave Type
        </a>
    </div>

    <!-- Leave Types Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-2"></div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Leave Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Default Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Carry Forward</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Requires Approval</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($leaveTypes as $type)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($type->color)
                                        <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center" style="background-color: {{ $type->color }}20;">
                                            <i class="fa-solid fa-tag" style="color: {{ $type->color }}"></i>
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 bg-teal-100 dark:bg-teal-900 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-tag text-teal-600 dark:text-teal-400"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $type->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($type->description ?? 'No description', 40) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $type->default_days }} days
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($type->carry_forward ?? false)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($type->requires_approval ?? true)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Required</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Auto-approve</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($type->is_active ?? true)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('hms.hr.leave-types.edit', $type) }}" class="text-teal-600 hover:text-teal-900 dark:text-teal-400 mr-3">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <button onclick="if(confirm('Delete this leave type?')) { document.getElementById('delete-type-{{ $type->id }}').submit(); }" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <form id="delete-type-{{ $type->id }}" action="{{ route('hms.hr.leave-types.destroy', $type) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fa-solid fa-calendar-xmark text-6xl text-gray-400 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">No leave types defined</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Create your first leave type to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leaveTypes->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $leaveTypes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
