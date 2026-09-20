@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Module Management</h1>
        <div class="flex gap-3">
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $enabledCount }} enabled</span>
            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">{{ $disabledCount }} disabled</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="mb-6">
        <form action="{{ route('admin.modules.enable-all') }}" method="POST" class="inline">
            @csrf
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enable All Modules</button>
        </form>
    </div>

    @foreach($categories as $category => $catModules)
        @continue(optional($catModules)->isEmpty())
        <div class="bg-white rounded shadow mb-6 overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">{{ $category }}</h2>
                <span class="text-sm text-gray-500">{{ count($catModules) }} module(s)</span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($catModules as $module)
                    <div class="px-5 py-3 flex items-center justify-between {{ $module->is_enabled ? '' : 'bg-gray-50' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $module->is_enabled ? 'bg-green-500' : 'bg-red-400' }}"></span>
                            <a href="{{ route('admin.modules.show', $module->slug) }}" class="text-gray-800 hover:text-blue-600 truncate">
                                {{ $module->name }}
                            </a>
                            @if(in_array($module->slug, \App\Models\Module::alwaysEnabledSlugs()))
                                <span class="text-xs px-2 py-0.5 bg-gray-200 text-gray-600 rounded-full">Core</span>
                            @endif
                        </div>
                        <form action="{{ route('admin.modules.toggle', $module) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            @if(in_array($module->slug, \App\Models\Module::alwaysEnabledSlugs()))
                                <button type="button" class="px-3 py-1 text-sm text-gray-400 cursor-not-allowed" title="Core module cannot be disabled" disabled>Core</button>
                            @else
                                <button type="submit" class="relative inline-flex items-center h-6 w-11 rounded-full transition-colors focus:outline-none {{ $module->is_enabled ? 'bg-green-500' : 'bg-gray-300' }}">
                                    <span class="inline-block w-4 h-4 transform transition-transform bg-white rounded-full shadow {{ $module->is_enabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            @endif
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
