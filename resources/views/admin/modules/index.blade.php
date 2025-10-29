@extends('admin.layouts.app')

@section('content')
    <div class="bg-white p-4 rounded shadow">
        <div class="font-semibold mb-4">Admin Modules</div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($modules as $m)
                <a href="{{ route('admin.modules.show', str($m)->slug('-')) }}" class="block p-4 border rounded hover:shadow">
                    {{ $m }}
                </a>
            @endforeach
        </div>
    </div>
@endsection


