<x-app-layout>
    <div class="p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Services Catalog</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage all billable services and their default prices</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('hms.pricing.price-lists.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition text-center">Price Lists</a>
                <a href="{{ route('hms.pricing.services.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-center">
                    <i class="fa fa-plus mr-2"></i> Add Service
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('hms.pricing.services.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, code..."
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                </div>
                <div>
                    <select name="category" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                        <option value="">All Categories</option>
                        @foreach(['consultation','lab','radiology','pharmacy','procedure','bed','meal','other'] as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Filter</button>
                    <a href="{{ route('hms.pricing.services.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">Reset</a>
                </div>
            </form>
        </div>

        <!-- Services Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Default Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($services as $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">{{ $service->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->name }}</div>
                                @if($service->description)
                                    <div class="text-xs text-gray-500">{{ $service->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $service->category === 'consultation' ? 'bg-blue-100 text-blue-800' : ($service->category === 'lab' ? 'bg-purple-100 text-purple-800' : ($service->category === 'radiology' ? 'bg-pink-100 text-pink-800' : ($service->category === 'bed' ? 'bg-indigo-100 text-indigo-800' : ($service->category === 'meal' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800')))) }}">
                                    {{ ucfirst($service->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ $service->currency }} {{ number_format($service->default_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('hms.pricing.services.edit', $service) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                <a href="{{ route('hms.pricing.services.show', $service) }}" class="text-green-600 hover:text-green-800 mr-3">View</a>
                                <form method="POST" action="{{ route('hms.pricing.services.destroy', $service) }}" class="inline" onsubmit="return confirm('Delete this service?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fa fa-inbox text-3xl mb-3"></i>
                                <p>No services found. Run the pricing seeder or add a service.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3">{{ $services->links() }}</div>
        </div>
    </div>
</x-app-layout>
