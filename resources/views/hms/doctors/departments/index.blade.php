@extends('admin.layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded shadow md:col-span-2">
            <div class="text-lg font-semibold mb-3">Doctor Departments</div>
            <table class="min-w-full text-sm">
                <thead><tr class="text-left"><th class="py-2">Name</th><th>Description</th></tr></thead>
                <tbody>
                    @forelse($departments as $dep)
                        <tr class="border-t"><td class="py-2">{{ $dep->name }}</td><td>{{ $dep->description }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="py-4 text-center text-gray-500">No departments</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $departments->links() }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-lg font-semibold mb-3">Create Department</div>
            @if(session('status'))
                <div class="mb-2 p-2 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif
            <form method="POST" action="{{ route('hms.doctors.departments.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm text-gray-700">Name</label>
                    <input name="name" class="mt-1 w-full border rounded p-2" required />
                    @error('name')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 w-full border rounded p-2"></textarea>
                </div>
                <button class="px-3 py-2 bg-indigo-600 text-white rounded">Save</button>
            </form>
        </div>
    </div>
@endsection


