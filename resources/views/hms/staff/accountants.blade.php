@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                <i class="fa fa-calculator text-blue-600 mr-3"></i>
                Accountants Management
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage accounting staff and their information</p>
        </div>
        <a href="{{ route('hms.staff.accountants.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa fa-user-plus mr-2"></i> Add Accountant
        </a>
    </div>

    <!-- Success Message -->
    @if(session('status'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
            <i class="fa fa-check-circle mr-2"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Accountants</div>
            <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ $accountants->total() }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Active Accountants</div>
            <div class="text-2xl font-semibold text-green-600">{{ $accountants->where('is_active', true)->count() }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">This Month</div>
            <div class="text-2xl font-semibold text-blue-600">{{ $accountants->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Licensed</div>
            <div class="text-2xl font-semibold text-purple-600">{{ $accountants->whereNotNull('license_number')->count() }}</div>
        </div>
    </div>

    <!-- Accountants Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Accountant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Qualification</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joining Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($accountants as $accountant)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr($accountant->first_name, 0, 1) }}{{ substr($accountant->last_name ?? '', 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $accountant->first_name }} {{ $accountant->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $accountant->accountant_id ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    <i class="fa fa-envelope text-gray-400 mr-1"></i> {{ $accountant->email }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    <i class="fa fa-phone text-gray-400 mr-1"></i> {{ $accountant->phone ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $accountant->qualification ?? 'N/A' }}
                                @if($accountant->certification)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $accountant->certification }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                @if($accountant->joining_date)
                                    {{ \Carbon\Carbon::parse($accountant->joining_date)->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full 
                                    {{ ($accountant->is_active ?? true) ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    @if($accountant->is_active ?? true)
                                        <i class="fa fa-check-circle mr-1"></i> Active
                                    @else
                                        <i class="fa fa-times-circle mr-1"></i> Inactive
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-green-600 hover:text-green-900 dark:text-green-400" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="#" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this accountant?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-400 dark:text-gray-500">
                                    <i class="fa fa-calculator text-6xl mb-4"></i>
                                    <p class="text-lg font-medium">No accountants found</p>
                                    <p class="text-sm mt-2">Start by adding a new accountant</p>
                                    <a href="{{ route('hms.staff.accountants.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                        <i class="fa fa-user-plus mr-2"></i> Add First Accountant
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($accountants->hasPages())
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                {{ $accountants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

