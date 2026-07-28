@extends('admin.layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold text-gray-800">{{ $module }}</h2>
            @if($route)
                <a href="{{ route($route) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Go to Module
                </a>
            @endif
        </div>
        
        <div class="border-t pt-4">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">Module Information</h3>
            <p class="text-gray-600 mb-4">
                The <strong>{{ $module }}</strong> module is available in the system. 
                @if($route)
                    Click the button above to access the module, or use the navigation menu.
                @else
                    Please use the main navigation menu to access this module's features.
                @endif
            </p>
            
            <div class="bg-blue-50 border border-blue-200 rounded p-4 mt-4">
                <h4 class="font-semibold text-blue-900 mb-2">Available Features:</h4>
                <ul class="list-disc list-inside text-blue-800 space-y-1">
                    <li>Full CRUD operations</li>
                    <li>Data management and reporting</li>
                    <li>Integration with other modules</li>
                    <li>User permissions and access control</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
