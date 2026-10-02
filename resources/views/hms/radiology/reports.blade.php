<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-x-ray text-blue-600 mr-3"></i> Radiology Reports
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                    <h3 class="font-semibold mb-3">By status</h3>
                    <table class="w-full text-sm">
                        @foreach(($statusStats ?? []) as $status => $count)
                            <tr class="border-b"><td class="py-1">{{ $status }}</td><td class="text-right font-bold">{{ $count }}</td></tr>
                        @endforeach
                        @if(empty($statusStats))
                            <tr><td class="text-gray-500 py-2">No data</td></tr>
                        @endif
                    </table>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5 lg:col-span-2">
                    <h3 class="font-semibold mb-3">Monthly requests</h3>
                    <table class="w-full text-sm">
                        <thead><tr class="text-left text-gray-500 border-b"><th class="py-1">Month</th><th>Requests</th></tr></thead>
                        <tbody>
                            @foreach(($monthlyRequests ?? []) as $row)
                                <tr class="border-b">
                                    <td class="py-1">{{ $row->month ?? $row->label ?? '—' }}</td>
                                    <td>{{ $row->total ?? $row->count ?? 0 }}</td>
                                </tr>
                            @endforeach
                            @if(empty($monthlyRequests))
                                <tr><td colspan="2" class="text-gray-500 py-2">No monthly data</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <h3 class="font-semibold mb-3">By test</h3>
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-gray-500 border-b"><th class="py-1">Test</th><th>Count</th></tr></thead>
                    <tbody>
                        @foreach(($testStats ?? []) as $row)
                            <tr class="border-b">
                                <td class="py-1">{{ $row->name ?? $row->test ?? '—' }}</td>
                                <td>{{ $row->total ?? $row->count ?? 0 }}</td>
                            </tr>
                        @endforeach
                        @if(empty($testStats))
                            <tr><td colspan="2" class="text-gray-500 py-2">No test data</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
