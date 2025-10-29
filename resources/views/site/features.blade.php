@extends('layouts.site')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-600 text-white py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">Our Features</h1>
                <p class="text-xl text-indigo-100 leading-relaxed">
                    Discover what makes us different. Advanced technology, compassionate care, and innovative solutions designed for your health and well-being.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Features Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach([
                [
                    'title' => '24/7 Emergency Services',
                    'desc' => 'Round-the-clock emergency medical care with rapid response teams ready to assist you anytime, anywhere.',
                    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                    'color' => 'red'
                ],
                [
                    'title' => 'Online Appointment Booking',
                    'desc' => 'Convenient online booking system allows you to schedule appointments from the comfort of your home or office.',
                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'color' => 'blue'
                ],
                [
                    'title' => 'Advanced Diagnostic Equipment',
                    'desc' => 'State-of-the-art diagnostic imaging and laboratory equipment ensuring accurate and timely diagnosis.',
                    'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    'color' => 'green'
                ],
                [
                    'title' => 'Pharmacy Integration',
                    'desc' => 'Fully integrated in-house pharmacy providing seamless medication dispensing and professional consultation.',
                    'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                    'color' => 'purple'
                ],
                [
                    'title' => 'Secure Electronic Medical Records',
                    'desc' => 'HIPAA-compliant electronic medical records system ensuring secure, accessible, and comprehensive patient data management.',
                    'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                    'color' => 'indigo'
                ],
                [
                    'title' => 'Health Insurance Support',
                    'desc' => 'Seamless integration with major insurance providers and assistance with claims processing for your convenience.',
                    'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    'color' => 'yellow'
                ],
            ] as $feature)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 p-8 group">
                    <div class="w-16 h-16 bg-{{ $feature['color'] }}-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-{{ $feature['color'] }}-600 transition-colors">
                        <svg class="w-8 h-8 text-{{ $feature['color'] }}-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Additional Features Section -->
    <section class="bg-gray-50 py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Technology & Patient Experience</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    We combine cutting-edge technology with compassionate care to deliver exceptional healthcare experiences
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Technology & Innovation</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Our commitment to innovation means you have access to the latest medical technologies:
                    </p>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Telemedicine consultations for remote care
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Digital health records accessible anytime
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Modern laboratory and imaging equipment
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            AI-powered diagnostic assistance
                        </li>
                    </ul>
                </div>
                <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 p-8 rounded-xl shadow-lg text-white">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Patient Experience</h3>
                    <p class="text-indigo-100 leading-relaxed mb-4">
                        Your comfort and care are our top priorities:
                    </p>
                    <ul class="space-y-2 text-indigo-100">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-white mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Comfortable, modern facilities
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-white mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Patient-centered care approach
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-white mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Comprehensive support services
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-white mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Multilingual staff and services
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-white mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Transparent pricing and billing
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4">Experience Premium Healthcare</h2>
            <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                Discover how our advanced features and compassionate care can benefit you and your family
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('book-appointment') }}" class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Book Appointment
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-4 bg-indigo-800 text-white font-bold rounded-lg hover:bg-indigo-900 transition-colors border-2 border-white/30">
                    Learn More
                </a>
            </div>
        </div>
    </section>
@endsection


