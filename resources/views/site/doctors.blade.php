@extends('layouts.site')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-600 text-white py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">Our Expert Doctors</h1>
                <p class="text-xl text-indigo-100 leading-relaxed">
                    Meet our team of experienced specialists dedicated to providing exceptional healthcare services
                </p>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="bg-white border-b py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ search: '', department: '' }">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="search"
                        placeholder="Search by name or specialty" 
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                        id="search-input"
                    />
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <select 
                        x-model="department"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white cursor-pointer"
                        id="department-filter"
                    >
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctors Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="doctors-grid">
            @forelse($doctors as $doctor)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group doctor-card" 
                     data-name="{{ strtolower($doctor->full_name) }}"
                     data-department="{{ $doctor->department_id ?? '' }}"
                     data-specialty="{{ strtolower($doctor->department->name ?? '') }}">
                    <div class="h-72 bg-gradient-to-br from-indigo-100 via-indigo-50 to-indigo-200 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                        <svg class="h-40 w-40 text-indigo-300 opacity-40 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Dr. {{ $doctor->full_name }}</h3>
                        <p class="text-indigo-600 font-semibold mb-2">{{ $doctor->department->name ?? 'General Practice' }}</p>
                        <div class="flex items-center text-gray-600 text-sm mb-4">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $doctor->years_experience ?? '5+' }} years of experience
                        </div>
                        @if($doctor->qualification)
                        <div class="text-gray-600 text-sm mb-4">
                            <span class="font-medium">Qualification:</span> {{ $doctor->qualification }}
                        </div>
                        @endif
                        <div class="flex gap-3">
                            <a href="{{ route('book-appointment') }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors group-hover:shadow-lg">
                                Book Appointment
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-xl text-gray-600 font-semibold mb-2">No doctors found</p>
                    <p class="text-gray-500">Please adjust your search criteria</p>
                </div>
            @endforelse
        </div>
        
        @if($doctors->hasPages())
            <div class="mt-12 flex justify-center">
                <div class="flex gap-2">
                    {{ $doctors->links() }}
                </div>
            </div>
        @endif

        <div class="mt-12 text-center">
            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-xl p-8 border border-indigo-200">
                <p class="text-lg text-gray-700 mb-4">
                    Our doctors combine expertise with compassion to ensure quality care. All our specialists are board-certified and committed to providing personalized treatment.
                </p>
                <a href="{{ route('book-appointment') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-lg hover:shadow-xl">
                    Book Your Consultation Today
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const departmentFilter = document.getElementById('department-filter');
            const doctorCards = document.querySelectorAll('.doctor-card');

            function filterDoctors() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedDepartment = departmentFilter.value;
                let visibleCount = 0;

                doctorCards.forEach(card => {
                    const name = card.dataset.name;
                    const department = card.dataset.department;
                    const specialty = card.dataset.specialty;

                    const matchesSearch = !searchTerm || 
                        name.includes(searchTerm) || 
                        specialty.includes(searchTerm);
                    
                    const matchesDepartment = !selectedDepartment || department === selectedDepartment;

                    if (matchesSearch && matchesDepartment) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show no results message if needed
                const noResults = document.querySelector('.no-results-message');
                if (visibleCount === 0 && (searchTerm || selectedDepartment)) {
                    if (!noResults) {
                        const grid = document.getElementById('doctors-grid');
                        const message = document.createElement('div');
                        message.className = 'col-span-full text-center py-16 no-results-message';
                        message.innerHTML = `
                            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <p class="text-xl text-gray-600 font-semibold mb-2">No doctors found</p>
                            <p class="text-gray-500">Please adjust your search criteria</p>
                        `;
                        grid.appendChild(message);
                    }
                } else if (noResults && visibleCount > 0) {
                    noResults.remove();
                }
            }

            searchInput.addEventListener('input', filterDoctors);
            departmentFilter.addEventListener('change', filterDoctors);
        });
    </script>
@endsection


