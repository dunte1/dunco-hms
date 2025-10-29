@extends('layouts.site')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-600 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Compassionate Care,<br>
                    <span class="text-indigo-200">Advanced Medicine,</span><br>
                    Trusted Professionals
                </h1>
                <p class="text-xl text-indigo-100 mb-8 leading-relaxed">
                    Comprehensive healthcare services delivered with excellence. Modern facilities, compassionate professionals, and cutting-edge technology dedicated to your well-being.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('book-appointment') }}" class="px-8 py-4 bg-white text-indigo-700 font-semibold rounded-lg shadow-lg hover:bg-indigo-50 transition-all duration-300 transform hover:scale-105 text-center">
                        Book Appointment
                    </a>
                    <a href="{{ route('doctors') }}" class="px-8 py-4 bg-indigo-700/50 backdrop-blur-sm text-white font-semibold rounded-lg border-2 border-white/30 hover:bg-indigo-700/70 transition-all duration-300 text-center">
                        Find a Doctor
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <!-- Stats Section -->
    <section class="relative bg-gray-50 -mt-12 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 md:gap-6">
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-100 text-center transform hover:-translate-y-1 transition-transform">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ number_format($stats['patients']) }}</div>
                    <div class="text-gray-600 text-sm font-medium">Patients Treated</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-100 text-center transform hover:-translate-y-1 transition-transform">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ number_format($stats['doctors']) }}</div>
                    <div class="text-gray-600 text-sm font-medium">Expert Doctors</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-100 text-center transform hover:-translate-y-1 transition-transform">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ number_format($stats['nurses']) }}</div>
                    <div class="text-gray-600 text-sm font-medium">Qualified Nurses</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-100 text-center transform hover:-translate-y-1 transition-transform">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ number_format($stats['happyPatients']) }}</div>
                    <div class="text-gray-600 text-sm font-medium">Happy Patients</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-100 text-center transform hover:-translate-y-1 transition-transform">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $stats['years'] }}+</div>
                    <div class="text-gray-600 text-sm font-medium">Years Experience</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Services Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Our Core Services</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Comprehensive medical services delivered with precision, care, and advanced technology
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors">
                    <svg class="w-8 h-8 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Diagnostics</h3>
                <p class="text-gray-600 mb-4">Advanced diagnostic imaging and laboratory services for accurate diagnosis</p>
                <a href="{{ route('services') }}" class="text-indigo-600 font-medium hover:text-indigo-700 inline-flex items-center">
                    Learn More <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors">
                    <svg class="w-8 h-8 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Surgery</h3>
                <p class="text-gray-600 mb-4">State-of-the-art surgical facilities with experienced surgical teams</p>
                <a href="{{ route('services') }}" class="text-indigo-600 font-medium hover:text-indigo-700 inline-flex items-center">
                    Learn More <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors">
                    <svg class="w-8 h-8 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Pharmacy</h3>
                <p class="text-gray-600 mb-4">Fully-stocked in-house pharmacy with professional consultation</p>
                <a href="{{ route('services') }}" class="text-indigo-600 font-medium hover:text-indigo-700 inline-flex items-center">
                    Learn More <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('services') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-lg hover:shadow-xl">
                View All Services
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </section>

    <!-- Featured Doctors Section -->
    @if($featuredDoctors->count() > 0)
    <section class="bg-gray-50 py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Meet Our Expert Doctors</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Experienced specialists dedicated to providing exceptional healthcare
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                @foreach($featuredDoctors->take(3) as $doctor)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group">
                    <div class="h-64 bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        <svg class="h-32 w-32 text-indigo-400 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Dr. {{ $doctor->full_name }}</h3>
                        <p class="text-indigo-600 font-medium mb-2">{{ $doctor->department->name ?? 'General Practice' }}</p>
                        <p class="text-gray-600 text-sm mb-4">{{ $doctor->years_experience ?? '5+' }} years of experience</p>
                        <a href="{{ route('book-appointment') }}" class="inline-flex items-center text-indigo-600 font-medium hover:text-indigo-700">
                            Book Appointment
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center">
                <a href="{{ route('doctors') }}" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg border-2 border-indigo-600 hover:bg-indigo-50 transition-colors shadow-md hover:shadow-lg">
                    View All Doctors
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Testimonials Section -->
    @if($testimonials->count() > 0)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">What Our Patients Say</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Real experiences from patients who trust us with their health
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonials as $testimonial)
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
                    </div>
                </div>
                <p class="text-gray-700 mb-4 italic">"{{ $testimonial->comment }}"</p>
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-indigo-600 font-semibold">{{ substr($testimonial->patient_name ?? 'Patient', 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">{{ $testimonial->patient_name ?? 'Anonymous' }}</div>
                        @if($testimonial->rating)
                        <div class="text-sm text-gray-500">Rating: {{ $testimonial->rating }}/5</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Emergency CTA Section -->
    <section class="bg-gradient-to-r from-red-600 to-red-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 backdrop-blur-sm p-4 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg font-semibold mb-1">Emergency Medical Services</div>
                        <div class="text-red-100">Available 24/7 - Immediate Response</div>
                    </div>
                </div>
                <a href="tel:+254700000000" class="px-8 py-4 bg-white text-red-600 font-bold rounded-lg shadow-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 whitespace-nowrap">
                    Call +254 700 000 000
                </a>
            </div>
        </div>
    </section>
@endsection


