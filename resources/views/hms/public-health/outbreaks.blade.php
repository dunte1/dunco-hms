<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">Outbreak Events</h1>
            @if(session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('status') }}</div>
            @endif
            <div class="bg-white shadow rounded-lg p-6">
                <p>Outbreak events listing.</p>
            </div>
        </div>
    </div>
</x-app-layout>
