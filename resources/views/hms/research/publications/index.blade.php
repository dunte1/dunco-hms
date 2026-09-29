<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">Publications</h1>
            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif
            @foreach($publications as $pub)
                <div class="mb-2 p-3 bg-white shadow rounded">{{ $pub->title }} - {{ $pub->status }}</div>
            @endforeach
            {{ $publications->links() }}
        </div>
    </div>
</x-app-layout>
