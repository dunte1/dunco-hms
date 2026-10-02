@extends('components.app-layout')

@section('title', 'Request Break-Glass Access')

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Request Emergency (Break-Glass) Access</h1>
    <p class="text-sm text-red-600 mb-6">
        Use only when normal authorization is insufficient for urgent patient care.
        A detailed reason is required and the action is fully audited for security review.
    </p>

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('break-glass.store') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Patient (optional)</label>
            <select name="patient_id" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                <option value="">â€” Select patient â€”</option>
                @foreach($patients as $p)
                    <option value="{{ $p->id }}">{{ $p->patient_no }} â€” {{ $p->first_name }} {{ $p->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Reason (required, min 20 characters)</label>
            <textarea name="reason" rows="4" required minlength="20" maxlength="1000"
                      class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"
                      placeholder="Describe the emergency, why normal access is insufficient, and what records are needed."></textarea>
            @error('reason')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Grant &amp; Audit Access
            </button>
            <a href="{{ route('break-glass.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection

