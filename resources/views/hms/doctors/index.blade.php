<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-user-md text-blue-600 mr-3"></i>
                        Doctors Management
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage all doctors and medical staff</p>
                </div>
                <a href="{{ route('hms.doctors.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> Add New Doctor
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Doctors</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-user-md text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Departments</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['departments'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-hospital text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Avg Experience</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['average_experience'] }} yrs</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-award text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Added This Month</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['added_this_month'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-user-plus text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('hms.doctors.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-search mr-1"></i> Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by name, email, phone, qualification..." 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fa fa-building mr-1"></i> Department
                        </label>
                        <select name="department" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="fa fa-filter mr-2"></i> Filter
                        </button>
                        <a href="{{ route('hms.doctors.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Doctors Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($doctors as $doctor)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8 text-center">
                            <div class="w-24 h-24 mx-auto bg-white rounded-full flex items-center justify-center text-blue-600 text-4xl font-bold shadow-lg">
                                {{ substr($doctor->first_name, 0, 1) }}{{ substr($doctor->last_name, 0, 1) }}
                            </div>
                            <h3 class="mt-4 text-xl font-bold text-white">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h3>
                            @if($doctor->department)
                                <p class="text-blue-100 text-sm mt-1">{{ $doctor->department->name }}</p>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-3 mb-4">
                                @if($doctor->qualification)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <i class="fa fa-graduation-cap text-blue-600 mr-2 w-5"></i>
                                        <span>{{ $doctor->qualification }}</span>
                                    </div>
                                @endif
                                
                                @if($doctor->years_experience)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <i class="fa fa-award text-blue-600 mr-2 w-5"></i>
                                        <span>{{ $doctor->years_experience }} years experience</span>
                                    </div>
                                @endif
                                
                                @if($doctor->email)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <i class="fa fa-envelope text-blue-600 mr-2 w-5"></i>
                                        <span class="truncate">{{ $doctor->email }}</span>
                                    </div>
                                @endif
                                
                                @if($doctor->phone)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <i class="fa fa-phone text-blue-600 mr-2 w-5"></i>
                                        <span>{{ $doctor->phone }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('hms.doctors.show', $doctor) }}" 
                                   class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg text-center transition">
                                    <i class="fa fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('hms.doctors.edit', $doctor) }}" 
                                   class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg text-center transition">
                                    <i class="fa fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('hms.doctors.destroy', $doctor) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                            <div class="text-gray-400 dark:text-gray-500">
                                <i class="fa fa-user-md text-6xl mb-4"></i>
                                <p class="text-lg font-medium">No doctors found</p>
                                <p class="text-sm mt-2">Start by adding a new doctor</p>
                                <a href="{{ route('hms.doctors.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                    <i class="fa fa-plus mr-2"></i> Add First Doctor
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($doctors->hasPages())
                <div class="mt-6">
                    {{ $doctors->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
