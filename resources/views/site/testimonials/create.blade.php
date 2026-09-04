@extends('layouts.app')

@section('title', 'Submit Testimonial')

@section('content')
<div class="container-fluid py-6">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-comment-dots text-amber-600 mr-3"></i> Share Your Experience
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">We value your feedback. Tell us about your experience with our hospital.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('site.testimonials.store') }}">
            @csrf

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Information</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department Visited</label>
                        <select name="department" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                            <option value="">Select department</option>
                            <option value="general" {{ old('department') == 'general' ? 'selected' : '' }}>General Medicine</option>
                            <option value="cardiology" {{ old('department') == 'cardiology' ? 'selected' : '' }}>Cardiology</option>
                            <option value="orthopedics" {{ old('department') == 'orthopedics' ? 'selected' : '' }}>Orthopedics</option>
                            <option value="pediatrics" {{ old('department') == 'pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                            <option value="pharmacy" {{ old('department') == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                            <option value="laboratory" {{ old('department') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                            <option value="emergency" {{ old('department') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            <option value="other" {{ old('department') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Testimonial</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rating *</label>
                        <div class="flex items-center gap-1" x-data="{ rating: {{ old('rating', 5) }} }">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" @click="rating = {{ $i }}; $refs.rating_input.value = {{ $i }}"
                                    :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-gray-300'"
                                    class="text-3xl focus:outline-none">
                                    <i class="fa-solid fa-star"></i>
                                </button>
                            @endfor
                            <input type="hidden" name="rating" x-ref="rating_input" value="{{ old('rating', 5) }}">
                            <span class="ml-2 text-sm text-gray-500" x-text="rating + '/5'"></span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Testimonial *</label>
                        <textarea name="content" rows="5" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500"
                            placeholder="Tell us about your experience...">{{ old('content') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Submit Testimonial
                </button>
                <a href="{{ route('site.testimonials.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-300 transition">
                    View All Testimonials
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
