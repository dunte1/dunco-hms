<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">HAI Surveillance Records</h1>
            @if(session('status'))
                <div class="mb-4 text-green-600">{{ session('status') }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
