@extends('admin.layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold text-gray-800">{{ $module }}</h2>
            @if($hasRoute)
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    ✅ Implemented
                </span>
            @else
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                    🚧 Coming Soon
                </span>
            @endif
        </div>
        
        @if($hasRoute)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Module Fully Implemented</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>This module is fully functional and ready for use. You can access all its features through the main navigation menu.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Module Under Development</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>This module is currently being developed and will be available soon. The core functionality is planned and will include:</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">Module Information</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><strong>Name:</strong> {{ $module }}</li>
                    <li><strong>Slug:</strong> {{ $slug }}</li>
                    <li><strong>Status:</strong> 
                        @if($hasRoute)
                            <span class="text-green-600">Fully Implemented</span>
                        @else
                            <span class="text-yellow-600">In Development</span>
                        @endif
                    </li>
                    <li><strong>Category:</strong> 
                        @if(str_contains($module, 'Management'))
                            Core Management
                        @elseif(str_contains($module, 'Reports'))
                            Reporting & Analytics
                        @elseif(str_contains($module, 'Settings'))
                            System Configuration
                        @else
                            Hospital Operations
                        @endif
                    </li>
                </ul>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">Quick Actions</h3>
                <div class="space-y-2">
                    @if($hasRoute)
                        <a href="{{ route('admin.modules.index') }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            Access Module
                        </a>
                    @else
                        <button disabled class="block w-full text-center px-4 py-2 bg-gray-300 text-gray-500 rounded cursor-not-allowed">
                            Coming Soon
                        </button>
                    @endif
                    <a href="{{ route('admin.modules.index') }}" class="block w-full text-center px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition-colors">
                        Back to Modules
                    </a>
                </div>
            </div>
        </div>

        @if(!$hasRoute)
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-blue-800 mb-2">Development Progress</h3>
                <div class="text-sm text-blue-700">
                    <p class="mb-2">This module is part of our comprehensive hospital management system and will include:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Complete CRUD operations</li>
                        <li>Role-based access control</li>
                        <li>Data validation and security</li>
                        <li>Integration with other modules</li>
                        <li>Reporting and analytics</li>
                    </ul>
                    <p class="mt-2 text-blue-600">
                        <strong>Expected completion:</strong> Next development phase
                    </p>
                </div>
            </div>
        @endif
    </div>
@endsection
