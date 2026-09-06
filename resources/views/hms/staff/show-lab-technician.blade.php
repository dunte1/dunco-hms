<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.staff.lab-technicians') }}" class="hover:text-blue-600">Lab Technicians</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Details</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-user text-blue-600 mr-3"></i>Lab Technician Details</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2"></div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><h3 class="text-sm font-medium text-gray-500">Name</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $technician->first_name }} {{ $technician->last_name }}</p></div>
                        <div><h3 class="text-sm font-medium text-gray-500">Email</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $technician->email }}</p></div>
                        <div><h3 class="text-sm font-medium text-gray-500">Phone</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $technician->phone }}</p></div>
                        <div><h3 class="text-sm font-medium text-gray-500">Technician ID</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $technician->technician_id }}</p></div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.staff.lab-technicians.edit', $technician) }}" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium"><i class="fa fa-edit mr-2"></i> Edit</a>
                        <a href="{{ route('hms.staff.lab-technicians') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
