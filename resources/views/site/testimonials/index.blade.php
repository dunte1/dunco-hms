@extends('layouts.app')

@section('title', 'Patient Testimonials')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-quote-left text-amber-600 mr-3"></i> Patient Testimonials
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">What our patients say about us</p>
        </div>
        <a href="{{ route('site.testimonials.create') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> Share Your Experience
        </a>
    </div>

    <!-- Testimonials Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($testimonials ?? [] as $testimonial)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 h-12 w-12 bg-amber-100 dark:bg-amber-900 rounded-full flex items-center justify-center">
                        <span class="text-lg font-bold text-amber-600 dark:text-amber-400">
                            {{ substr($testimonial->name ?? 'A', 0, 1) }}
                        </span>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $testimonial->name ?? 'Anonymous' }}</h3>
                        @if($testimonial->department ?? false)
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($testimonial->department) }}</p>
                        @endif
                    </div>
                </div>

                <!-- Rating -->
                <div class="flex items-center mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i <= ($testimonial->rating ?? 5) ? 'text-amber-400' : 'text-gray-300' }} text-sm"></i>
                    @endfor
                </div>

                <!-- Content -->
                <div class="mb-4">
                    <i class="fa-solid fa-quote-left text-amber-200 dark:text-amber-800 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-700 dark:text-gray-300 italic leading-relaxed">
                        {{ $testimonial->content ?? 'No testimonial content' }}
                    </p>
                </div>

                <div class="text-xs text-gray-400">
                    {{ $testimonial->created_at?->diffForHumans() ?? '' }}
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-12 text-center">
                <i class="fa-solid fa-quote-left text-6xl text-gray-400 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 dark:text-white">No testimonials yet</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Be the first to share your experience</p>
                <a href="{{ route('site.testimonials.create') }}" class="mt-4 inline-block px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition">
                    Share Your Experience
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
