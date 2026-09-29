<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">Trainee Records</h1>
            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif
            @foreach($trainees as $trainee)
                <div class="mb-2 p-3 bg-white shadow rounded">{{ $trainee->name }} - {{ $trainee->trainee_type }}</div>
            @endforeach
            {{ $trainees->links() }}
        </div>
    </div>
</x-app-layout>
