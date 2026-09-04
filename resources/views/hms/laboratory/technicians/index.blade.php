@extends('layouts.app')

@section('title', 'Lab Technicians')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-flask text-teal-600 mr-3"></i> Lab Technicians
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage laboratory technicians</p>
        </div>
        <a href="{{ route('hms.laboratory.technicians.create') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> Add Technician
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-2"></div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Technician</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Specialization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Shift</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($technicians as $tech)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-teal-100 dark:bg-teal-900 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-semibold text-teal-600">{{ substr($tech->first_name ?? 'T', 0, 1) }}{{ substr($tech->last_name ?? '', 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $tech->first_name ?? '' }} {{ $tech->last_name ?? '' }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $tech->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $tech->technician_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $tech->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $tech->specialization ?? 'General' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-teal-100 text-teal-800">{{ ucfirst($tech->shift ?? 'Day') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-teal-600 hover:text-teal-900 dark:text-teal-400 mr-3"><i class="fa-solid fa-eye"></i></a>
                                <a href="#" class="text-green-600 hover:text-green-900 dark:text-green-400"><i class="fa-solid fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fa-solid fa-flask text-6xl text-gray-400 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">No lab technicians found</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Add your first lab technician to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($technicians->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $technicians->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
