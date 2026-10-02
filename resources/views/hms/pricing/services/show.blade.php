<x-app-layout>
    <div class="p-6">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Service Details</h1>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><span class="text-gray-500">Code:</span><span class="font-bold ml-2">{{ $service->code }}</span></div>
                    <div><span class="text-gray-500">Category:</span><span class="font-bold ml-2">{{ ucfirst($service->category) }}</span></div>
                    <div><span class="text-gray-500">Name:</span><span class="font-bold ml-2">{{ $service->name }}</span></div>
                    <div><span class="text-gray-500">Price:</span><span class="font-bold ml-2">{{ $service->currency }} {{ number_format($service->default_price, 2) }}</span></div>
                    <div><span class="text-gray-500">Status:</span><span class="ml-2 {{ $service->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span></div>
                    <div><span class="text-gray-500">Taxable:</span><span class="ml-2">{{ $service->is_taxable ? 'Yes' : 'No' }}</span></div>
                </div>
                @if($service->description)
                    <div><span class="text-gray-500">Description:</span><p class="mt-1">{{ $service->description }}</p></div>
                @endif
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('hms.pricing.services.edit', $service) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Edit</a>
                    <a href="{{ route('hms.pricing.services.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
