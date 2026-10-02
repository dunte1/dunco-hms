<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-ambulance text-red-600 mr-3"></i> Ambulance Trips
                </h1>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Ambulance</th>
                                <th class="px-4 py-3 text-left">Patient</th>
                                <th class="px-4 py-3 text-left">Pickup</th>
                                <th class="px-4 py-3 text-left">Drop-off</th>
                                <th class="px-4 py-3 text-left">Departure</th>
                                <th class="px-4 py-3 text-left">Arrival</th>
                                <th class="px-4 py-3 text-left">Distance</th>
                                <th class="px-4 py-3 text-left">Type</th>
                                <th class="px-4 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($trips as $trip)
                                <tr>
                                    <td class="px-4 py-3">{{ $trip->id }}</td>
                                    <td class="px-4 py-3">{{ optional($trip->ambulance)->registration_number ?? optional($trip->ambulance)->plate_number ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ optional($trip->patient)->full_name ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $trip->pickup_location }}</td>
                                    <td class="px-4 py-3">{{ $trip->dropoff_location }}</td>
                                    <td class="px-4 py-3">{{ optional($trip->departure_time)->format('d M H:i') }}</td>
                                    <td class="px-4 py-3">{{ optional($trip->arrival_time)->format('d M H:i') }}</td>
                                    <td class="px-4 py-3">{{ $trip->distance_km }} km</td>
                                    <td class="px-4 py-3">{{ $trip->trip_type }}</td>
                                    <td class="px-4 py-3">{{ $trip->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-8 text-center text-gray-500">No trips recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
