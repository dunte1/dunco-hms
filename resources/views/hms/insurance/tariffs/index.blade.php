<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Insurance Tariffs</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tariff codes and provider pricing</p>
                </div>
            </div>

            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code, name..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                <select name="category" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    <option value="">All categories</option>
                    @foreach(($categories ?? collect()) as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Filter</button>
            </form>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provider</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($tariffs ?? []) as $tariff)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium">{{ $tariff->code }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $tariff->name }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $tariff->category ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $tariff->insuranceProvider->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ number_format($tariff->amount ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No tariffs found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($tariffs ?? null, 'links'))
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $tariffs->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
