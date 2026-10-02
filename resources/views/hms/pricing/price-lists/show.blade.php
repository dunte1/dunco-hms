<x-app-layout>
    <div class="p-6">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Price List Details</h1>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><span class="text-gray-500">Name:</span><span class="font-bold ml-2">{{ $priceList->name }}</span></div>
                    <div><span class="text-gray-500">Currency:</span><span class="font-bold ml-2">{{ $priceList->currency }}</span></div>
                    <div><span class="text-gray-500">Status:</span><span class="ml-2 {{ $priceList->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $priceList->is_active ? 'Active' : 'Inactive' }}</span></div>
                    <div><span class="text-gray-500">Default:</span><span class="ml-2">{{ $priceList->is_default ? 'Yes' : 'No' }}</span></div>
                    @if($priceList->effective_from)<div><span class="text-gray-500">From:</span><span class="ml-2">{{ $priceList->effective_from->format('M d, Y') }}</span></div>@endif
                    @if($priceList->effective_to)<div><span class="text-gray-500">To:</span><span class="ml-2">{{ $priceList->effective_to->format('M d, Y') }}</span></div>@endif
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Price Items ({{ $priceList->items->count() }})</h3>
                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="text-left text-sm font-medium text-gray-500">Service</th>
                                <th class="text-left text-sm font-medium text-gray-500">Category</th>
                                <th class="text-right text-sm font-medium text-gray-500">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($priceList->items as $item)
                            <tr>
                                <td class="py-2 text-sm text-gray-900 dark:text-white">{{ $item->service->name ?? 'N/A' }}</td>
                                <td class="py-2 text-sm text-gray-500">{{ ucfirst($item->service->category ?? 'N/A') }}</td>
                                <td class="py-2 text-sm text-right font-bold">{{ $priceList->currency }} {{ number_format($item->price, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">No items</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('hms.pricing.price-lists.edit', $priceList) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Edit</a>
                    <a href="{{ route('hms.pricing.price-lists.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
