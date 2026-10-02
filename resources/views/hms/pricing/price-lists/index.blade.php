<x-app-layout>
    <div class="p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Price Lists</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage pricing tiers and effective dates</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('hms.pricing.services.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-center">Services</a>
                <a href="{{ route('hms.pricing.price-lists.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-center">
                    <i class="fa fa-plus mr-2"></i> Add Price List
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Currency</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Default</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($priceLists as $pl)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $pl->name }}</div>
                                @if($pl->description)<div class="text-xs text-gray-500">{{ $pl->description }}</div>@endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $pl->currency }}</td>
                            <td class="px-6 py-4 text-sm">{{ $pl->items->count() }}</td>
                            <td class="px-6 py-4">
                                @if($pl->is_default)<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Default</span>@endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $pl->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $pl->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('hms.pricing.price-lists.show', $pl) }}" class="text-green-600 hover:text-green-800 mr-3">View</a>
                                <a href="{{ route('hms.pricing.price-lists.edit', $pl) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                <form method="POST" action="{{ route('hms.pricing.price-lists.destroy', $pl) }}" class="inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fa fa-inbox text-3xl mb-3"></i><p>No price lists found.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3">{{ $priceLists->links() }}</div>
        </div>
    </div>
</x-app-layout>
